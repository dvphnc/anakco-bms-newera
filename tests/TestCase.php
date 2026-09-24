<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Safety net: feature tests wipe the database they use (RefreshDatabase).
     * Refuse to run at all unless it's a dedicated test database — e.g. if a
     * cached or misconfigured config sent the tests to the real anakco_bms.
     *
     * This lives in createApplication() on purpose: it runs before any trait
     * touches the database, and no trait overrides it. (RefreshDatabase has its
     * own beforeRefreshingDatabase(), which would silently replace a guard
     * defined there.)
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $connection = $app['config']->get('database.default');
        $database   = (string) $app['config']->get("database.connections.$connection.database");

        if ($database !== ':memory:' && ! str_contains($database, 'testing')) {
            throw new RuntimeException(
                "Refusing to run tests against the \"$database\" database: tests wipe the database they use. "
                .'Tests must use anakco_bms_testing (see phpunit.xml). If the config is cached, run `php artisan config:clear`.'
            );
        }

        return $app;
    }
}
