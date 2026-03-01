<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommitteeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_slug',
        'record_type',
        'title',
        'description',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeForCommittee($query, string $slug)
    {
        return $query->where('committee_slug', $slug);
    }

    public function scopePhotos($query)
    {
        return $query->where('record_type', 'Photo');
    }

    public function scopeReports($query)
    {
        return $query->where('record_type', 'Report');
    }

    public function scopeResolutions($query)
    {
        return $query->where('record_type', 'Resolution');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isImage(): bool
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'webp']);
    }

    public function isPdf(): bool
    {
        return strtolower($this->file_type) === 'pdf';
    }
}