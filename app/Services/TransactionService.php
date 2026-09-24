<?php

namespace App\Services;

use App\Enums\ResidencyStatus;
use App\Exceptions\DuplicateClaimException;
use App\Models\AssistanceProgram;
use App\Models\Household;
use App\Models\ReliefSupply;
use App\Models\ReliefSupplyMovement;
use App\Models\Resident;
use App\Models\ResidentTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Task 1.1 — resident transaction history and double-claim prevention.
 *
 * Claims against a program are checked against its rule (e.g. once per
 * household) while the household / resident row is locked, and a unique
 * claim_lock column makes the database itself reject a second claim, so two
 * PCs submitting at the same moment can't both succeed.
 */
class TransactionService
{
    /**
     * Record something a resident received through the Transactions tab.
     *
     * @throws DuplicateClaimException when the program's claim limit is reached
     * @throws ValidationException when the resident / program can't be used
     */
    public function record(Resident $resident, array $data, User $by): ResidentTransaction
    {
        if ($resident->residency_status !== ResidencyStatus::Alive->value) {
            throw ValidationException::withMessages([
                'resident' => "Transactions can only be recorded for living residents. {$resident->full_name} is marked as {$resident->residency_label}.",
            ]);
        }

        $program = ! empty($data['assistance_program_id'])
            ? AssistanceProgram::findOrFail($data['assistance_program_id'])
            : null;

        if ($program && ! $program->isOpen()) {
            throw ValidationException::withMessages([
                'assistance_program_id' => "“{$program->name}” is not open for claims right now.",
            ]);
        }

        $attributes = [
            'resident_id'           => $resident->id,
            'household_id'          => $resident->household_id,
            'assistance_program_id' => $program?->id,
            'type'                  => $program ? $program->type : $data['type'],
            'description'           => $data['description'] ?? $program?->name,
            'quantity'              => $data['quantity'] ?? null,
            'unit'                  => $data['unit'] ?? null,
            'amount'                => $data['amount'] ?? null,
            'transacted_at'         => $data['transacted_at'] ?? now(),
            'processed_by'          => $by->id,
        ];

        if (! $program) {
            return $this->create($attributes);
        }

        return DB::transaction(function () use ($resident, $program, $attributes) {
            [$scope, $scopeId] = $this->scopeFor($program, $resident);

            // Serialize claims for this household / resident until we commit
            ($scope === 'household' ? Household::class : Resident::class)::whereKey($scopeId)->lockForUpdate()->first();

            $prior = $this->claimsInScope($program, $scope, $scopeId);
            if ($prior->count() >= $program->max_claims) {
                throw $this->duplicate($program, $prior->first());
            }

            // Lock the program's relief supplies too, so two PCs can't hand out
            // the last pack twice
            $supplies = $this->lockSupplies($program);
            if ($short = $program->shortSupplies()) {
                throw ValidationException::withMessages([
                    'assistance_program_id' => $this->outOfStock($program, $short),
                ]);
            }

            $attributes['claim_lock'] = $program->max_claims === 1
                ? $program->id.':'.($scope === 'household' ? 'H' : 'R').$scopeId
                : null;

            try {
                $transaction = $this->create($attributes);
            } catch (UniqueConstraintViolationException) {
                // Another PC recorded the same claim a moment ago
                throw $this->duplicate($program, $this->claimsInScope($program, $scope, $scopeId)->first());
            }

            foreach ($supplies as $supply) {
                $supply->adjust(-$supply->pivot->quantity_per_claim, "Given out — {$transaction->reference_no}", $transaction);
            }

            return $transaction;
        });
    }

    /**
     * Can this resident claim from this program right now? Read-only; used for
     * the check shown before staff submit.
     */
    public function eligibility(Resident $resident, AssistanceProgram $program): array
    {
        [$scope, $scopeId] = $this->scopeFor($program, $resident);
        $prior = $this->claimsInScope($program, $scope, $scopeId);
        $first = $prior->first();

        $program->load('supplies');
        $short = $program->shortSupplies();

        $reason = match (true) {
            $resident->residency_status !== ResidencyStatus::Alive->value => "{$resident->full_name} is marked as {$resident->residency_label}.",
            ! $program->isOpen()                                          => 'This program is not open for claims right now.',
            $prior->count() >= $program->max_claims                        => $this->duplicate($program, $first)->getMessage(),
            $short !== []                                                  => $this->outOfStock($program, $short),
            default                                                        => null,
        };

        return [
            'eligible'    => $reason === null,
            'reason'      => $reason,
            'scope'       => $scope,
            'claims_used' => $prior->count(),
            'max_claims'  => $program->max_claims,
            'claims_left' => $program->claimsLeft(),   // null = not linked to relief stock
            'prior'       => $first ? [
                'reference_no' => $first->reference_no,
                'resident'     => $first->resident?->full_name,
                'date'         => $first->transacted_at->format('m/d/Y'),
                'same_person'  => $first->resident_id === $resident->id,
            ] : null,
        ];
    }

    /** Void a mistaken entry. It stays on record, and its claim is freed. */
    public function void(ResidentTransaction $transaction, User $by, string $reason): void
    {
        DB::transaction(function () use ($transaction, $by, $reason) {
            // Locked, so two PCs voiding at once can't return the stock twice
            $current = ResidentTransaction::whereKey($transaction->id)->lockForUpdate()->first();

            if ($current->is_voided) {
                throw ValidationException::withMessages(['reason' => 'This transaction was already voided.']);
            }

            $transaction->forceFill([
                'voided_at'   => now(),
                'voided_by'   => $by->id,
                'void_reason' => $reason,
                'claim_lock'  => null,
            ])->save();

            // Put back exactly what this claim took, even if the program's list changed since
            $taken = ReliefSupplyMovement::where('resident_transaction_id', $transaction->id)
                ->where('change', '<', 0)
                ->get();

            $supplies = ReliefSupply::whereIn('id', $taken->pluck('relief_supply_id'))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            foreach ($taken as $movement) {
                $supplies[$movement->relief_supply_id]?->adjust(
                    -$movement->change, "Returned — {$transaction->reference_no} voided", $transaction
                );
            }
        });
    }

    /**
     * History-only entry written automatically by other modules (a released
     * document, medicine handed out). No claim rule; recorded once per source.
     */
    public function logFromSource(Model $source, Resident $resident, string $type, string $description, array $extra = []): ?ResidentTransaction
    {
        $exists = ResidentTransaction::where('source_type', $source->getMorphClass())
            ->where('source_id', $source->getKey())
            ->valid()
            ->exists();

        if ($exists) {
            return null;
        }

        return $this->create(array_merge([
            'resident_id'   => $resident->id,
            'household_id'  => $resident->household_id,
            'type'          => $type,
            'source_type'   => $source->getMorphClass(),
            'source_id'     => $source->getKey(),
            'description'   => $description,
            'transacted_at' => now(),
            'processed_by'  => auth()->id(),
        ], $extra));
    }

    // -------------------------------------------------------------------------

    /** The program's supplies, locked in id order (a fixed order avoids deadlocks). */
    private function lockSupplies(AssistanceProgram $program)
    {
        $supplies = $program->supplies()->reorder('committee_relief_supplies.id')->lockForUpdate()->get();
        $program->setRelation('supplies', $supplies);

        return $supplies;
    }

    private function outOfStock(AssistanceProgram $program, array $short): string
    {
        return 'Not enough stock for this program: '.implode(', ', $short)
            .'. Add stock under Committees → BDRRM → Relief Supplies.';
    }

    /** Per-household programs fall back to the resident if they have no household. */
    private function scopeFor(AssistanceProgram $program, Resident $resident): array
    {
        return $program->claim_scope === 'household' && $resident->household_id
            ? ['household', $resident->household_id]
            : ['resident', $resident->id];
    }

    private function claimsInScope(AssistanceProgram $program, string $scope, int $scopeId)
    {
        return ResidentTransaction::with('resident')
            ->where('assistance_program_id', $program->id)
            ->valid()
            ->where($scope === 'household' ? 'household_id' : 'resident_id', $scopeId)
            ->orderBy('transacted_at')
            ->get();
    }

    private function duplicate(AssistanceProgram $program, ?ResidentTransaction $prior): DuplicateClaimException
    {
        $who = $prior?->resident?->full_name ?? 'someone';
        $scope = $program->claim_scope === 'household' ? ' (same household)' : '';
        $message = $prior
            ? "Already claimed by {$who}{$scope} on {$prior->transacted_at->format('m/d/Y')} — {$prior->reference_no}."
            : 'The claim limit for this program has been reached.';

        return new DuplicateClaimException($prior, $message);
    }

    private function create(array $attributes): ResidentTransaction
    {
        $transaction = ResidentTransaction::create($attributes);
        $transaction->forceFill([
            'reference_no' => sprintf('TXN-%s-%06d', $transaction->transacted_at->format('Y'), $transaction->id),
        ])->save();

        return $transaction;
    }
}
