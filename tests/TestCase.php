<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        // RefreshDatabase ya se encarga de ejecutar las migraciones automáticamente
        // Solo verificamos que la conexión sea la correcta
        $this->assertDatabaseConnectionIsTest();
    }

    /**
     * Verificar que estamos usando la base de datos de tests
     */
    protected function assertDatabaseConnectionIsTest(): void
    {
        $database = config('database.connections.mysql.database');
        
        // En entorno de testing, la base de datos debe ser clinora_test
        if (app()->environment('testing')) {
            if ($database !== 'clinora_test' && $database !== ':memory:') {
                throw new \RuntimeException(
                    "Los tests deben usar la base de datos 'clinora_test' o ':memory:', pero se está usando '{$database}'. " .
                    "Verifica tu configuración en phpunit.xml"
                );
            }
        }
    }

    /**
     * Seed the database with test data
     */
    protected function seedTestData(): void
    {
        $this->seed(\Database\Seeders\TestSeeder::class);
    }
}
