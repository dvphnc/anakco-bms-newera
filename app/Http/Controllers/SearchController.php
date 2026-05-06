<?php

namespace App\Http\Controllers;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\Household;
use App\Models\Official;
use App\Models\Resident;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        $role = auth()->user()->role;

        if (strlen($q) < 2) {
            return response()->json(['results' => [], 'query' => $q]);
        }

        // Committee role can only search officials and committees
        $canSearchRecords = in_array($role, ['Admin', 'Secretary']);

        $results = [];

        // Residents — Admin + Secretary only
        if ($canSearchRecords) {
            Resident::where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$q}%"])
                    ->orWhereRaw("CONCAT(last_name, ', ', first_name) like ?", ["%{$q}%"])
                    ->orWhere('contact_number', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            })->limit(5)->get()->each(function ($r) use (&$results) {
                $results[] = [
                    'type' => 'Resident',
                    'icon' => 'fa-user',
                    'color' => '#22c55e',
                    'title' => $r->last_name.', '.$r->first_name,
                    'subtitle' => $r->address.' · '.($r->residency_status ?? ''),
                    'url' => route('residents.show', $r->id),
                    'badge' => $r->residency_status,
                    'badge_class' => $r->residency_status === 'Active' ? 'badge-green' : 'badge-gray',
                ];
            });
        }

        // Households — Admin + Secretary only
        if ($canSearchRecords) {
            Household::where('household_number', 'like', "%{$q}%")
                ->orWhere('household_head', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%")
                ->with('purok')->limit(4)->get()->each(function ($h) use (&$results) {
                    $results[] = [
                        'type' => 'Household',
                        'icon' => 'fa-house',
                        'color' => '#3b82f6',
                        'title' => $h->household_number.' — '.($h->household_head ?? 'No Head'),
                        'subtitle' => $h->address.' · '.($h->purok->name ?? ''),
                        'url' => route('households.show', $h->id),
                        'badge' => 'Household',
                        'badge_class' => 'badge-blue',
                    ];
                });
        }

        // Documents — Admin + Secretary only
        if ($canSearchRecords) {
            Document::where('doc_number', 'like', "%{$q}%")
                ->orWhere('document_type', 'like', "%{$q}%")
                ->orWhere('or_number', 'like', "%{$q}%")
                ->orWhereHas('resident', fn ($r) => $r->whereRaw("CONCAT(first_name,' ',last_name) like ?", ["%{$q}%"]))
                ->with('resident')->limit(4)->get()->each(function ($d) use (&$results) {
                    $results[] = [
                        'type' => 'Document',
                        'icon' => 'fa-file-alt',
                        'color' => '#f59e0b',
                        'title' => $d->doc_number.' — '.$d->document_type,
                        'subtitle' => $d->resident->full_name ?? '—',
                        'url' => route('documents.show', $d->id),
                        'badge' => $d->status,
                        'badge_class' => $d->status === 'Released' ? 'badge-green' : 'badge-yellow',
                    ];
                });
        }

        // Blotter — Admin + Secretary only
        if ($canSearchRecords) {
            BlotterCase::where('case_number', 'like', "%{$q}%")
                ->orWhere('complainant_name', 'like', "%{$q}%")
                ->orWhere('respondent_name', 'like', "%{$q}%")
                ->orWhere('incident_type', 'like', "%{$q}%")
                ->orWhere('incident_location', 'like', "%{$q}%")
                ->limit(4)->get()->each(function ($b) use (&$results) {
                    $results[] = [
                        'type' => 'Blotter',
                        'icon' => 'fa-gavel',
                        'color' => '#ef4444',
                        'title' => $b->case_number.' — '.$b->incident_type,
                        'subtitle' => ($b->complainant_name ?? '—').' vs '.($b->respondent_name ?? '—'),
                        'url' => route('blotter.show', $b->id),
                        'badge' => $b->status,
                        'badge_class' => $b->status === 'Settled' ? 'badge-green' : 'badge-red',
                    ];
                });
        }

        // Businesses — Admin + Secretary only
        if ($canSearchRecords) {
            Business::where('business_name', 'like', "%{$q}%")
                ->orWhere('permit_number', 'like', "%{$q}%")
                ->orWhere('owner_name', 'like', "%{$q}%")
                ->orWhere('business_address', 'like', "%{$q}%")
                ->limit(4)->get()->each(function ($b) use (&$results) {
                    $results[] = [
                        'type' => 'Business',
                        'icon' => 'fa-store',
                        'color' => '#f97316',
                        'title' => $b->business_name,
                        'subtitle' => $b->owner_name.' · '.$b->permit_number,
                        'url' => route('businesses.show', $b->id),
                        'badge' => $b->status,
                        'badge_class' => $b->status === 'Active' ? 'badge-green' : 'badge-red',
                    ];
                });
        }

        // Officials
        Official::where('full_name', 'like', "%{$q}%")
            ->orWhere('position', 'like', "%{$q}%")
            ->orWhere('committee', 'like', "%{$q}%")
            ->limit(3)->get()->each(function ($o) use (&$results) {
                $results[] = [
                    'type' => 'Official',
                    'icon' => 'fa-user-tie',
                    'color' => '#7c3aed',
                    'title' => $o->full_name,
                    'subtitle' => $o->position.($o->committee ? ' · '.$o->committee : ''),
                    'url' => route('officials.show', $o->id),
                    'badge' => $o->is_active ? 'Active' : 'Inactive',
                    'badge_class' => $o->is_active ? 'badge-green' : 'badge-gray',
                ];
            });

        return response()->json([
            'results' => $results,
            'query' => $q,
            'total' => count($results),
        ]);
    }
}
