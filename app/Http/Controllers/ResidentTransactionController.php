<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateClaimException;
use App\Models\AssistanceProgram;
use App\Models\Resident;
use App\Models\ResidentTransaction;
use App\Services\TransactionService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

/**
 * Task 1.1 — the Transactions tab on a resident's profile.
 */
class ResidentTransactionController extends Controller
{
    use LogsActivity;

    public function __construct(private TransactionService $transactions)
    {
    }

    // History table (server-side DataTable)
    public function index(Request $request, Resident $resident)
    {
        $query = ResidentTransaction::with(['program', 'processedBy', 'voidedBy'])
            ->where('resident_id', $resident->id)
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->select('resident_transactions.*');

        return DataTables::of($query)
            ->addColumn('date_col', fn ($t) => '<span style="white-space:nowrap">'.$t->transacted_at->format('m/d/Y').'</span>'
                .'<div class="td-muted" style="font-size:11.5px">'.$t->transacted_at->format('g:i A').'</div>')
            ->addColumn('item_col', function ($t) {
                $html = '<span class="badge '.$t->type_badge.'" style="margin-right:6px">'.e($t->type_label).'</span>'
                    .'<span style="font-weight:600'.($t->is_voided ? ';text-decoration:line-through;color:var(--text-subtle)' : '').'">'.e($t->description).'</span>';
                if ($t->program) {
                    $html .= '<div class="td-muted" style="margin-top:3px">'.e($t->program->name).'</div>';
                }
                if ($t->is_voided) {
                    $html .= '<div style="font-size:12px;color:var(--crimson);margin-top:3px">Voided '.e($t->voided_at->format('m/d/Y'))
                        .' by '.e($t->voidedBy->name ?? '—').': '.e($t->void_reason).'</div>';
                }

                return $html;
            })
            ->addColumn('qty_col', function ($t) {
                $parts = [];
                if ($t->quantity !== null) {
                    $parts[] = rtrim(rtrim(number_format((float) $t->quantity, 2), '0'), '.').($t->unit ? ' '.e($t->unit) : '');
                }
                if ($t->amount !== null) {
                    $parts[] = '₱'.number_format((float) $t->amount, 2);
                }

                return $parts ? implode('<br>', $parts) : '<span class="td-muted">—</span>';
            })
            ->addColumn('ref_col', fn ($t) => '<span class="td-mono">'.e($t->reference_no).'</span>'
                .'<div class="td-muted" style="font-size:11.5px">'.e($t->processedBy->name ?? 'System').'</div>')
            ->addColumn('actions', function ($t) {
                if ($t->is_voided) {
                    return '';
                }

                return '<button type="button" class="btn btn-secondary btn-sm" onclick="openVoidModal('.$t->id.', \''.e(addslashes($t->reference_no)).'\')" title="Void this entry">'
                    .'<i class="fas fa-ban" style="color:var(--crimson)"></i> Void</button>';
            })
            ->rawColumns(['date_col', 'item_col', 'qty_col', 'ref_col', 'actions'])
            ->make(true);
    }

    public function store(Request $request, Resident $resident)
    {
        $data = $request->validate([
            'assistance_program_id' => ['nullable', 'integer', Rule::exists('assistance_programs', 'id')->whereNull('deleted_at')],
            'type'          => ['required_without:assistance_program_id', 'nullable', Rule::in(array_keys(ResidentTransaction::TYPES))],
            'description'   => ['required_without:assistance_program_id', 'nullable', 'string', 'max:255'],
            'quantity'      => ['nullable', 'numeric', 'min:0.01', 'max:99999'],
            'unit'          => ['nullable', 'string', 'max:30'],
            'amount'        => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'transacted_at' => ['nullable', 'date', 'before_or_equal:now'],
        ], [
            'type.required_without'        => 'Choose a program, or pick the type of item given.',
            'description.required_without' => 'Choose a program, or describe what was given.',
            'transacted_at.before_or_equal'=> 'The date cannot be in the future.',
        ]);

        try {
            $transaction = $this->transactions->record($resident, $data, $request->user());
        } catch (DuplicateClaimException $e) {
            return $this->fail($request, ['assistance_program_id' => $e->getMessage()]);
        }

        $this->logActivity('created', $transaction);
        $message = "Recorded {$transaction->reference_no} for {$resident->full_name}.";

        return $request->wantsJson()
            ? response()->json(['message' => $message, 'reference_no' => $transaction->reference_no])
            : redirect()->route('residents.show', [$resident, 'tab' => 'transactions'])->with('success', $message);
    }

    // Pre-check shown in the Record Transaction dialog before staff submit
    public function eligibility(Resident $resident, AssistanceProgram $program)
    {
        return response()->json($this->transactions->eligibility($resident, $program));
    }

    public function void(Request $request, ResidentTransaction $transaction)
    {
        $data = $request->validate([
            'reason' => 'required|string|min:5|max:255',
        ], [
            'reason.required' => 'Please give a reason for voiding this entry.',
            'reason.min'      => 'Please give a short reason (at least 5 characters).',
        ]);

        $oldData = $transaction->getOriginal();
        $this->transactions->void($transaction, $request->user(), $data['reason']);
        $this->logActivity('voided', $transaction, $oldData, $transaction->fresh()->toArray());

        $message = "{$transaction->reference_no} was voided.";

        return $request->wantsJson()
            ? response()->json(['message' => $message])
            : back()->with('success', $message);
    }

    private function fail(Request $request, array $errors)
    {
        return $request->wantsJson()
            ? response()->json(['message' => reset($errors), 'errors' => array_map(fn ($m) => [$m], $errors)], 422)
            : back()->withInput()->withErrors($errors);
    }
}
