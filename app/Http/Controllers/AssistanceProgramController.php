<?php

namespace App\Http\Controllers;

use App\Models\AssistanceProgram;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Task 1.1 — assistance / distribution programs and their claiming rules.
 */
class AssistanceProgramController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $programs = AssistanceProgram::withCount(['transactions as claims_count' => fn ($q) => $q->whereNull('voided_at')])
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get();

        return view('programs.programs-index', compact('programs'));
    }

    public function create()
    {
        return view('programs.programs-form', ['program' => new AssistanceProgram([
            'claim_scope' => 'household', 'max_claims' => 1, 'is_active' => true, 'starts_on' => now(),
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        $program = AssistanceProgram::create($data);
        $this->logActivity('created', $program);

        return redirect()->route('programs.index')->with('success', "Program “{$program->name}” created.");
    }

    public function edit(AssistanceProgram $program)
    {
        return view('programs.programs-form', compact('program'));
    }

    public function update(Request $request, AssistanceProgram $program)
    {
        $oldData = $program->getOriginal();
        $program->update($this->validated($request));
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
