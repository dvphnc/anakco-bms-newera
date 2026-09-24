<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Safety net: RefreshDatabase wipes the database it runs against. Refuse to
     * touch anything that isn't a dedicated test database — e.g. if a cached or
     * misconfigured config sent the tests to the real anakco_bms database.
     * Runs before RefreshDatabase changes anything.
     */
    protected function beforeRefreshingDatabase(): void
    {
        $connection = config('database.default');
        $database   = (string) config("database.connections.$connection.database");

        if ($database !== ':memory:' && ! str_contains($database, 'testing')) {
            throw new RuntimeException(
                "Refusing to run tests against the \"$database\" database: tests wipe the database they use. "
                .'Tests must use anakco_bms_testing (see phpunit.xml). If the config is cached, run `php artisan config:clear`.'
            );
        }
    }
}
