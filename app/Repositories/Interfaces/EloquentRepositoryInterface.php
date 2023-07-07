<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * Get all models
     *
     * @param array|string[] $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []) : Collection;
    /**
     * Count models
     *
     */

    public function count() : int;

    /**
     * Get all models
     *
     * @param array|string[] $columns
     * @param array $relations
     * @return Collection
     */
    public function getByCondition(array $condition, array $relations = [], array $columns = ['*']) : Collection;

    /**
     * Find model by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ) : ?Model;

    /**
     * Create a model
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload) : bool;

    /**
     * Delete model by id
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId) : bool;
}
