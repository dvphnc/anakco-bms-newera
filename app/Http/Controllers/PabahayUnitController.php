<?php

namespace App\Http\Controllers;

use App\Models\Pabahay;
use App\Models\PabahayUnit;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Part 2.2: the units inside a Pabahay. Admin only (route middleware: can:manage-religion-data).
 */
class PabahayUnitController extends Controller
{
    // Accepts one unit number, or several separated by commas / new lines ("A-1, A-2, A-3")
    public function store(Request $request, Pabahay $pabahay)
    {
        $request->validate(['unit_numbers' => 'required|string|max:1000'], [
            'unit_numbers.required' => 'Type at least one unit number, e.g. A-1.',
        ]);

        $numbers = collect(preg_split('/[,\n\r]+/', $request->input('unit_numbers')))
            ->map(fn ($n) => trim($n))->filter()->unique()->values();

        $tooLong = $numbers->first(fn ($n) => mb_strlen($n) > 30);
        if ($tooLong) {
            return back()->withInput()->with('error', "“{$tooLong}” is too long. Unit numbers can have up to 30 characters.");
        }

        $existing = $pabahay->units()->pluck('unit_no')->map(fn ($n) => mb_strtolower($n));
        [$already, $new] = $numbers->partition(fn ($n) => $existing->contains(mb_strtolower($n)));

        foreach ($new as $number) {
            $pabahay->units()->create(['unit_no' => $number]);
        }

        $message = $new->isEmpty()
            ? 'Nothing added: '.$already->join(', ').' already exist in this Pabahay.'
            : $new->count().' unit(s) added: '.$new->join(', ').'.'.($already->isNotEmpty() ? ' Skipped (already there): '.$already->join(', ').'.' : '');

        return redirect()->route('pabahays.show', $pabahay)->with($new->isEmpty() ? 'error' : 'success', $message);
    }

    // Rename, change the note, or switch a unit on / off
    public function update(Request $request, PabahayUnit $unit)
    {
        $data = $request->validate([
            'unit_no'   => ['sometimes', 'required', 'string', 'max:30', Rule::unique('pabahay_units', 'unit_no')->where('pabahay_id', $unit->pabahay_id)->ignore($unit->id)],
            'notes'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'unit_no.required' => 'The unit number cannot be empty.',
            'unit_no.unique'   => 'This Pabahay already has a unit with that number.',
        ]);

        if (array_key_exists('is_active', $data) && ! $data['is_active']
            && Resident::active()->where('pabahay_unit_id', $unit->id)->exists()) {
            return back()->with('error', "People still live in Unit {$unit->unit_no}. Move them to another unit first, then turn it off.");
        }

        $unit->update($data);

        return redirect()->route('pabahays.show', $unit->pabahay_id)->with('success', "Unit {$unit->unit_no} updated.");
    }
}
