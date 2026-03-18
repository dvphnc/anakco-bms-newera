<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected function logActivity(string $action, Model $model, array $oldData = [], array $newData = []): void
    {
        $changes = [];

        if ($action === 'updated' && !empty($oldData) && !empty($newData)) {
            foreach ($newData as $key => $newVal) {
                $oldVal = $oldData[$key] ?? null;
                // Only log fields that actually changed
                if ((string)$oldVal !== (string)$newVal) {
                    // Skip sensitive fields
                    if (in_array($key, ['password', 'remember_token', 'updated_at'])) continue;
                    $changes[$key] = [
                        'old' => $oldVal,
                        'new' => $newVal,
                    ];
                }
            }
        }

        ActivityLog::log($action, $model, $changes);
    }
}
