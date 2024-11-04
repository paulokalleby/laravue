<?php

namespace App\Repositories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Builder;

class TypeRepository
{
    protected $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function getAll(array $filters = [])
    {
        $query =  $this->type->with('brand', 'category')->when($filters, function (Builder $query) use ($filters) {

            if (isset($filters['brand_id']))   $query->where('brand_id', 'LIKE', "%{$filters['brand_id']}%");

            if (isset($filters['category_id']))   $query->where('category_id', 'LIKE', "%{$filters['category_id']}%");

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
        return $this->type->with('brand', 'category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->type->create($data);
    }

    public function update(array $data, string $id)
    {
        $type = $this->type->findOrFail($id);

        $type->update($data);

        return response()->json([
            'message' => 'success'
        ], 204);
    }

    public function delete(string $id)
    {
        $type = $this->type->findOrFail($id);

        $type->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getTypesHasModels()
    {
        return $this->type->whereHas('models')->get();
    }

    public function getTypesHasVehicles()
    {
        return $this->type->whereHas('vehicles')->get();
    }
}
