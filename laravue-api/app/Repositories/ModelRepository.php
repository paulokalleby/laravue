<?php

namespace App\Repositories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;

class ModelRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [])
    {
        $query =  $this->model->with('type')->when($filters, function (Builder $query) use ($filters) {

            if (isset($filters['type_id']))   $query->where('type_id', 'LIKE', "%{$filters['type_id']}%");

            if (isset($filters['name']))   $query->where('name', 'LIKE', "%{$filters['name']}%");

            if (isset($filters['active'])) {

                if ($filters['active'])  $query->whereActive(true);
                else  $query->whereActive(false);
            };
        });

        if (
            isset($filters['paginate']) &&
            is_numeric($filters['paginate'])
        )
            return $query->paginate($filters['paginate']);
        else
            return $query->get();
    }

    public function findById(string $id)
    {
        return $this->model->with('type')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, string $id)
    {
        $model = $this->model->findOrFail($id);

        $model->update($data);

        return response()->json([
            'message' => 'success'
        ], 204);
    }

    public function delete(string $id)
    {
        $model = $this->model->findOrFail($id);

        $model->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getModelsHasVehicles()
    {
        return $this->model->whereHas('vehicles')->get();
    }
}
