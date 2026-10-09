<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpsoMember extends Model
{
    use Archivable;

    use HasFactory;

    protected $table = 'committee_bpso';

    protected $fillable = ['full_name', 'rank', 'badge_number', 'contact_number', 'assignment', 'status'];
}
