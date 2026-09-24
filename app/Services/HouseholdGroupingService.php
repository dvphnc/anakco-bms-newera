<?php

namespace App\Services;

use App\Enums\ResidencyStatus;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * Task 1.2 — automatic household grouping.
 *
 * Residents at the same (normalized) address in the same purok are placed in one
 * household. Staff can override this per resident (household_assignment =
 * 'manual'); a manual choice is never overwritten.
 *
 * A household's head, family size and "voter household" flag are derived from its
 * living members here, so they can't drift out of date.
 */
class HouseholdGroupingService
{
    /**
     * Run after a resident is saved. Groups the resident by address when that
     * applies, then refreshes every household whose membership may have changed.
     */
    public function residentSaved(Resident $resident, ?int $previousHouseholdId): void
    {
        if ($this->shouldAutoGroup($resident)) {
            $household = $this->householdFor($resident);

            if ($resident->household_id !== $household->id) {
                $resident->household_id = $household->id;
                $resident->saveQuietly();   // no events: avoids re-entering this method
            }
        }

        if ($resident->relationship_to_head === 'Head' && $resident->household_id
            && $resident->residency_status === ResidencyStatus::Alive->value) {
            $this->makeHead(Household::find($resident->household_id), $resident);
        }

        foreach (array_unique(array_filter([$previousHouseholdId, $resident->household_id])) as $id) {
            if ($household = Household::find($id)) {
                $this->refresh($household);
            }
        }
    }

    public function shouldAutoGroup(Resident $resident): bool
    {
        return $resident->household_assignment !== 'manual'
            && $resident->residency_status === ResidencyStatus::Alive->value
            && filled($resident->address_key);
    }

    /** The household at this resident's address — created if there isn't one yet. */
    public function householdFor(Resident $resident): Household
    {
        $find = fn () => Household::where('purok_id', $resident->purok_id)
            ->where('address_key', $resident->address_key)
            ->first();

        if ($household = $find()) {
            return $household;
        }

        for ($attempt = 1; ; $attempt++) {
            try {
                return Household::create([
                    'household_number' => Household::nextNumber(),
                    'purok_id'         => $resident->purok_id,
                    'address'          => $resident->address,
                    'family_size'      => 0,
                ]);
            } catch (UniqueConstraintViolationException $e) {
                // Another PC registered someone at this address, or took the same
                // household number, at the same moment: use theirs, or retry.
                if ($household = $find()) {
                    return $household;
                }
                if ($attempt >= 5) {
                    throw $e;
                }
            }
        }
    }

    /** Make this resident the head of the household (one "Head" per household). */
    public function makeHead(Household $household, Resident $resident): void
    {
        $household->residents()
            ->whereKeyNot($resident->id)
            ->where('relationship_to_head', 'Head')
            ->update(['relationship_to_head' => null]);

        if ($resident->relationship_to_head !== 'Head') {
            $resident->relationship_to_head = 'Head';
            $resident->saveQuietly();
        }

        $household->forceFill(['head_resident_id' => $resident->id])->saveQuietly();
        $this->refresh($household);
    }

    /**
     * Recompute the head (current head if still living here → member marked "Head"
     * → eldest living member), family size and voter-household flag.
     */
    public function refresh(Household $household): void
    {
        $living = $household->livingMembers()->orderBy('birthdate')->orderBy('id')->get();

        $head = $living->firstWhere('id', $household->head_resident_id)
            ?? $living->firstWhere('relationship_to_head', 'Head')
            ?? $living->first();

        $household->forceFill([
            'head_resident_id'   => $head?->id,
            'household_head'     => $head?->full_name,
            'family_size'        => $living->count(),
            'is_voter_household' => $living->contains(fn (Resident $r) => $r->is_voter),
        ])->saveQuietly();
    }
}
