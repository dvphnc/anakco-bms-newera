<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected function logActivity(string $action, Model $model, array $oldData = [], array $newData = []): void
    {
        $changes = [];

        $skipFields = [
            'password', 'remember_token', 'updated_at', 'created_at',
            'deleted_at', 'photo_path', 'file_path', 'file_type', 'file_original_name',
        ];

        $booleanFields = [
            'is_voter', 'is_pwd', 'is_senior', 'is_solo_parent', 'is_4ps',
            'is_active', 'is_voter_household', 'is_4ps_beneficiary',
            'has_electricity', 'has_water', 'has_internet',
        ];

        if ($action === 'updated' && !empty($oldData) && !empty($newData)) {
            foreach ($newData as $key => $newVal) {
                if (in_array($key, $skipFields)) continue;

                $oldVal = $oldData[$key] ?? null;

                // Normalize booleans
                if (in_array($key, $booleanFields)) {
                    $oldVal = $oldVal ? 'Yes' : 'No';
                    $newVal = $newVal ? 'Yes' : 'No';
                }

                // Normalize dates — strip time part for comparison
                if (is_string($oldVal) && preg_match('/^\d{4}-\d{2}-\d{2}/', $oldVal)) {
                    $oldVal = substr($oldVal, 0, 10);
                }
                if (is_string($newVal) && preg_match('/^\d{4}-\d{2}-\d{2}/', $newVal)) {
                    $newVal = substr($newVal, 0, 10);
                }

                // Skip if truly unchanged
                if ((string)$oldVal === (string)$newVal) continue;

                $changes[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }

        // Only log if there are actual changes (for updates)
        if ($action === 'updated' && empty($changes)) return;

        ActivityLog::log($action, $model, $changes);
    }
}