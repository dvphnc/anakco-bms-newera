<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use LogicException;

/**
 * Part 3.1: records are archived, never erased.
 *
 * - delete() archives the record (sets deleted_at) and remembers who did it (deleted_by).
 * - Archived records disappear from lists and counts, and can be restored from the Recycle Bin.
 * - forceDelete() is refused, so a mistake or a bad actor cannot wipe data from the app.
 *   Maintenance scripts that remove demo records use PermanentDelete::allow().
 */
trait Archivable
{
    use SoftDeletes;

    public static function bootArchivable(): void
    {
        static::softDeleted(function ($model) {
            $by = auth()->id();
            $model->newQueryWithoutScopes()->whereKey($model->getKey())->update(['deleted_by' => $by]);
            $model->setAttribute('deleted_by', $by);
            $model->syncOriginalAttribute('deleted_by');
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
        });

        static::forceDeleting(function () {
            if (! PermanentDelete::allowed()) {
                throw new LogicException('Records cannot be permanently deleted. Archive them instead.');
            }
        });
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }
}
