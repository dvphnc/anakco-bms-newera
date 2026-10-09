<?php

namespace App\Models\Concerns;

/**
 * The one switch that allows a real (permanent) delete. Off by default, so the app can only
 * archive. Maintenance scripts that remove demo or test records wrap their work in allow().
 */
final class PermanentDelete
{
    private static bool $allowed = false;

    public static function allowed(): bool
    {
        return self::$allowed;
    }

    public static function allow(callable $callback)
    {
        self::$allowed = true;
        try {
            return $callback();
        } finally {
            self::$allowed = false;
        }
    }
}
