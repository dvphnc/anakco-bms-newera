<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Models\Household;
use App\Models\Resident;
use App\Support\RecycleBin;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Part 3.1: archived records can be looked at and restored here. There is no permanent delete.
 */
class RecycleBinController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $type   = RecycleBin::has((string) $request->type) ? $request->type : null;
        $search = trim((string) $request->search) ?: null;

        $items = RecycleBin::items($type, $search);
        $page  = max(1, (int) $request->page);
        $rows  = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, 25)->values(), $items->count(), 25, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('recycle-bin.index', [
            'rows'   => $rows,
            'counts' => RecycleBin::counts(),
            'types'  => RecycleBin::TYPES,
            'type'   => $type,
        ]);
    }

    public function restore(string $type, int $id)
    {
        abort_unless(RecycleBin::has($type), 404);

        $class  = RecycleBin::modelFor($type);
        $record = $class::onlyTrashed()->findOrFail($id);
        $name   = RecycleBin::nameOf($record);

        DB::transaction(function () use ($record) {
            $record->restore();
            $this->logActivity('restored', $record);

            // An appointment and the document issued from it are archived together, so they come back together
            if ($record instanceof DocumentAppointment) {
                Document::onlyTrashed()->where('appointment_id', $record->id)->get()
                    ->each(function (Document $doc) {
                        $doc->restore();
                        $this->logActivity('restored', $doc);
                    });
            }

            // A restored resident needs a household that is not archived
            if ($record instanceof Resident && $record->household_id) {
                $household = Household::onlyTrashed()->find($record->household_id);
                if ($household) {
                    $household->restore();
                    $this->logActivity('restored', $household);
                }
            }
        });

        return back()->with('success', RecycleBin::TYPES[$type][0]." \"{$name}\" restored.");
    }
}
