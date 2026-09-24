<?php

namespace App\Http\Controllers;

use App\Enums\ResidencyStatus;
use App\Models\Resident;
use App\Models\ResidentStatusLog;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * The only way a resident's life status (Alive / Deceased / Moved Out) changes
 * after registration. Every change records its effective date, who made it,
 * and an entry in resident_status_logs.
 */
class ResidentStatusController extends Controller
{
    use LogsActivity;

    public function update(Request $request, Resident $resident)
    {
        // Reversing a death record is a correction — Admins only.
        if ($resident->residency_status === ResidencyStatus::Deceased->value && ! $request->user()->isAdmin()) {
            abort(403, 'Only an Administrator can change the status of a resident recorded as deceased.');
        }

        $data = $request->validate([
            'to_status'      => ['required', Rule::in(ResidencyStatus::values())],
            'effective_date' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:'.$resident->birthdate->toDateString()],
            'moved_to'       => ['nullable', 'required_if:to_status,'.ResidencyStatus::MovedOut->value, 'string', 'max:255'],
            'remarks'        => ['nullable', 'string', 'max:1000'],
        ], [
            'to_status.required'            => 'Please choose the new status.',
            'to_status.in'                  => 'Please choose a valid status.',
            'effective_date.required'       => 'Please enter the date this happened.',
            'effective_date.before_or_equal'=> 'The date cannot be in the future.',
            'effective_date.after_or_equal' => 'The date cannot be before the resident\'s date of birth.',
            'moved_to.required_if'          => 'Please enter where the resident moved to.',
        ]);

        if ($data['to_status'] === $resident->residency_status) {
            throw ValidationException::withMessages([
                'to_status' => 'This resident is already marked as '.ResidencyStatus::labelFor($resident->residency_status).'.',
            ]);
        }

        $isMovedOut = $data['to_status'] === ResidencyStatus::MovedOut->value;
        $oldData    = $resident->getOriginal();

        DB::transaction(function () use ($resident, $data, $isMovedOut, $request) {
            ResidentStatusLog::create([
                'resident_id'    => $resident->id,
                'from_status'    => $resident->residency_status,
                'to_status'      => $data['to_status'],
                'effective_date' => $data['effective_date'],
                'moved_to'       => $isMovedOut ? $data['moved_to'] : null,
                'remarks'        => $data['remarks'] ?? null,
                'changed_by'     => $request->user()->id,
            ]);

            $resident->residency_status      = $data['to_status'];
            $resident->status_effective_date = $data['effective_date'];
            $resident->save();
        });

        $this->logActivity('updated', $resident, $oldData, $resident->fresh()->toArray());

        $message = "{$resident->full_name} is now marked as ".ResidencyStatus::labelFor($data['to_status']).'.';

        if ($request->wantsJson()) {
            return response()->json([
                'message'          => $message,
                'residency_status' => $resident->residency_status,
                'residency_label'  => $resident->residency_label,
                'residency_badge'  => $resident->residency_badge,
            ]);
        }

        return redirect()->route('residents.show', $resident)->with('success', $message);
    }
}
