<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use App\Enums\ResidencyStatus;
use Illuminate\Database\Eloquent\Model;

class PabahayUnit extends Model
{
    use Archivable;

    protected $fillable = ['pabahay_id', 'unit_no', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pabahay()
    {
        return $this->belongsTo(Pabahay::class)->withTrashed();
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    /** People who live there now (not deceased or moved out). */
    public function livingResidents()
    {
        return $this->residents()->where('residency_status', ResidencyStatus::Alive->value);
    }

    /** Unit numbers in natural order: 2 before 10, A-2 before A-10. */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw('LENGTH(unit_no), unit_no');
    }

    /** "Pabahay A · Unit 3" */
    public function getLabelAttribute(): string
    {
        return ($this->pabahay?->name ?? 'Pabahay').' · Unit '.$this->unit_no;
    }
}
