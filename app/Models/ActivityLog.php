<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
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
        return $this->belongsTo(User::class);
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    // -------------------------------------------------------
    // Static helper — call this from any controller
    // -------------------------------------------------------
    public static function log(string $action, Model $model, array $changes = []): void
    {
        static::create([
            'loggable_type' => get_class($model),
            'loggable_id'   => $model->id,
            'action'        => $action,
            'user_id'       => auth()->id(),
            'changes'       => $changes,
        ]);
    }
}