<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function business(string $permitNumber)
    {
        $business = Business::where('permit_number', $permitNumber)
            ->with(['ownerResident', 'issuedBy'])
            ->first();

        return view('verify.business', compact('business', 'permitNumber'));
    }
}
