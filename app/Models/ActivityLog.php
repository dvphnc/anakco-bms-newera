<?php

namespace App\Models;

use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use Archivable;

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'action',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function loggable()
    {
        return $this->morphTo()->withTrashed();
    }

    // -------------------------------------------------------
    // Static helper — call this from any controller
    // -------------------------------------------------------
    public static function log(string $action, Model $model, array $changes = []): void
    {
        static::create([
            'loggable_type' => get_class($model),
            'loggable_id' => $model->id,
            'action' => $action,
            'user_id' => auth()->id(),
            'changes' => $changes,
        ]);
    }
}
