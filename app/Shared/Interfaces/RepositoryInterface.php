<?php

namespace App\Shared\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Repository Interface
 * 
 * All repositories should implement this interface to ensure
 * consistent data access patterns across the application.
 */
interface RepositoryInterface
{
    /**
     * Find a record by its ID
     *
     * @param string $id
     * @return Model|null
     */
    public function find(string $id): ?Model;

    /**
     * Get all records with optional filters
     *
     * @param array $filters
     * @return Collection
     */
    public function findAll(array $filters = []): Collection;

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing record
     *
     * @param string $id
     * @param array $data
     * @return Model
     */
    public function update(string $id, array $data): Model;

    /**
     * Delete a record
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
