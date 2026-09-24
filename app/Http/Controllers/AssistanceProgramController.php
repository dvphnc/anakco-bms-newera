<?php

namespace App\Http\Controllers;

use App\Models\AssistanceProgram;
use App\Models\ReliefSupply;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Task 1.1 — assistance / distribution programs and their claiming rules.
 */
class AssistanceProgramController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $programs = AssistanceProgram::with('supplies')
            ->withCount(['transactions as claims_count' => fn ($q) => $q->whereNull('voided_at')])
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get();

        return view('programs.programs-index', compact('programs'));
    }

    public function create()
    {
        return $this->form(new AssistanceProgram([
            'claim_scope' => 'household', 'max_claims' => 1, 'is_active' => true, 'starts_on' => now(),
        ]));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        $program = DB::transaction(function () use ($data, $request) {
            $program = AssistanceProgram::create($data);
            $program->supplies()->sync($this->validatedSupplies($request));

            return $program;
        });
        $this->logActivity('created', $program);

        return redirect()->route('programs.index')->with('success', "Program “{$program->name}” created.");
    }

    public function edit(AssistanceProgram $program)
    {
        return $this->form($program->load('supplies'));
    }

    public function update(Request $request, AssistanceProgram $program)
    {
        $oldData = $program->getOriginal();
        $data = $this->validated($request);
        $supplies = $this->validatedSupplies($request);
        DB::transaction(function () use ($program, $data, $supplies) {
            $program->update($data);
            // Only affects claims from now on; past claims keep what they took
            $program->supplies()->sync($supplies);
        });
        $this->logActivity('updated', $program, $oldData, $program->fresh()->toArray());

        return redirect()->route('programs.index')->with('success', "Program “{$program->name}” updated.");
    }

    // Archive (soft delete). Its claims stay in every resident's history.
    public function destroy(AssistanceProgram $program)
    {
        $this->logActivity('deleted', $program);
        $program->delete();

        return redirect()->route('programs.index')->with('success', "Program “{$program->name}” archived. Its claims remain in residents' histories.");
    }

    private function form(AssistanceProgram $program)
    {
        return view('programs.programs-form', [
            'program'  => $program,
            'supplies' => ReliefSupply::orderBy('item_name')->orderBy('date_received')->get(),
        ]);
    }

    /** "Each claim uses" rows → [relief_supply_id => ['quantity_per_claim' => n]] for sync(). */
    private function validatedSupplies(Request $request): array
    {
        $rows = collect($request->input('supplies', []))
            ->filter(fn ($row) => filled($row['relief_supply_id'] ?? null) || filled($row['quantity_per_claim'] ?? null))
            ->values()
            ->all();

        validator(['supplies' => $rows], [
            'supplies'                      => 'array|max:20',
            'supplies.*.relief_supply_id'   => ['required', 'integer', 'distinct', Rule::exists('committee_relief_supplies', 'id')],
            'supplies.*.quantity_per_claim' => 'required|integer|min:1|max:10000',
        ], [
            'supplies.*.relief_supply_id.required'   => 'Choose a supply item, or remove the empty row.',
            'supplies.*.relief_supply_id.distinct'   => 'Each supply item can only be listed once.',
            'supplies.*.quantity_per_claim.required' => 'Enter how many each claim uses.',
            'supplies.*.quantity_per_claim.min'      => 'Each claim must use at least 1.',
        ])->validate();

        return collect($rows)->mapWithKeys(fn ($row) => [
            (int) $row['relief_supply_id'] => ['quantity_per_claim' => (int) $row['quantity_per_claim']],
        ])->all();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => ['required', Rule::in(array_keys(AssistanceProgram::TYPES))],
            'claim_scope' => 'required|in:household,resident',
            'max_claims'  => 'required|integer|min:1|max:100',
            'starts_on'   => 'nullable|date',
            'ends_on'     => 'nullable|date|after_or_equal:starts_on',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required'          => 'Please give the program a name, e.g. "Relief Pack — Typhoon Kristine".',
            'ends_on.after_or_equal' => 'The end date cannot be before the start date.',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
