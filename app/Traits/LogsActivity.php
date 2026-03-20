<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected function logActivity(string $action, Model $model, array $oldData = [], array $newData = []): void
    {
        $skipFields = [
            'password', 'remember_token', 'updated_at', 'created_at',
            'deleted_at', 'photo_path', 'file_path', 'file_type', 'file_original_name',
        ];

        $booleanFields = [
            'is_voter', 'is_pwd', 'is_senior', 'is_solo_parent', 'is_4ps',
            'is_active', 'is_voter_household', 'is_4ps_beneficiary',
            'has_electricity', 'has_water', 'has_internet',
        ];

        $friendlyNames = [
            'purok_id'     => 'Purok',
            'household_id' => 'Household',
            'resident_id'  => 'Resident',
            'issued_by'    => 'Issued By',
            'filed_by'     => 'Filed By',
            'user_id'      => 'User',
            'leader_id'    => 'Leader',
        ];

        $changes = [];

        if ($action === 'updated' && !empty($oldData) && !empty($newData)) {
            foreach ($newData as $key => $newVal) {
                if (in_array($key, $skipFields)) continue;

                $oldVal = $oldData[$key] ?? null;

                // Normalize booleans
                if (in_array($key, $booleanFields)) {
                    $oldVal = $oldVal ? 'Yes' : 'No';
                    $newVal = $newVal ? 'Yes' : 'No';
                }

                // Normalize dates — extract just Y-m-d from any format
                $oldVal = $this->normalizeValue($oldVal);
                $newVal = $this->normalizeValue($newVal);

                // Skip if truly unchanged
                if ((string)$oldVal === (string)$newVal) continue;

                $label = $friendlyNames[$key] ?? $key;
                $changes[$label] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }

        // Skip no-op updates
        if ($action === 'updated' && empty($changes)) return;

        ActivityLog::log($action, $model, $changes);
    }

    private function normalizeValue($val): string
    {
        if (is_null($val)) return '';
        if (is_array($val)) return '[file]';
        if (is_bool($val)) return $val ? 'Yes' : 'No';

        $str = (string) $val;

        // Strip time from datetime strings: 2022-09-20 00:00:00 or 2022-09-20T00:00:00...
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $str, $m)) {
            return $m[1];
        }

        return $str;
    }
}