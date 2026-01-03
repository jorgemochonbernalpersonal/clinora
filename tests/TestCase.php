<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Asegurar que estamos usando la base de datos de test
        $this->artisan('migrate', ['--database' => 'mysql'])->run();
    }

    /**
     * Seed the database with test data
     */
    protected function seedTestData(): void
    {
        $this->seed(\Database\Seeders\TestSeeder::class);
    }
}
