<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array|string[] $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->where('school_id', auth()->user()->school_id)->get($columns);
    }

    public function count(): int
    {
        return $this->model->where('school_id', auth()->user()->school_id)->count();
    }
    
    /**
     * @param array $condition
     * @param array $relations
     * @param array|string[] $columns
     * @return Collection
     */

    public function getByCondition(array $condition, array $relations = [], array $columns = ['*']): Collection
    {
        return $this->model->where($condition)->with($relations)->where('school_id', auth()->user()->school_id)->get($columns);
    }

    /**
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
    ): ?Model
    {
        return $this->model->select($columns)->with($relations)->where('school_id', auth()->user()->school_id)->findOrFail($modelId)->append($appends);
    }

    /**
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    /**
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        return $model->update($payload);
    }

    /**
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        if (property_exists($this, 'deleteAble')) {
            $this->deleteAble($modelId);
        }
        return $this->findById($modelId)->delete();
    }
}
