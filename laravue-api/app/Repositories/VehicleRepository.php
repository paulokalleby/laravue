<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;

class VehicleRepository
{
    protected $vehicle;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function getAll(array $filters = [])
    {
        $query =  $this->vehicle->with('brand', 'category', 'color', 'type', 'model')
            ->when($filters, function (Builder $query) use ($filters) {

                if (isset($filters['brand_id']))   $query->where('brand_id', 'LIKE', "%{$filters['brand_id']}%");

                if (isset($filters['category_id']))   $query->where('category_id', 'LIKE', "%{$filters['category_id']}%");

                if (isset($filters['color_id']))   $query->where('color_id', 'LIKE', "%{$filters['color_id']}%");

                if (isset($filters['type_id']))   $query->where('type_id', 'LIKE', "%{$filters['type_id']}%");

                if (isset($filters['model_id']))   $query->where('model_id', 'LIKE', "%{$filters['model_id']}%");

                if (isset($filters['plate']))   $query->where('plate', 'LIKE', "%{$filters['plate']}%");

                if (isset($filters['fuel']))   $query->where('fuel', 'LIKE', "%{$filters['fuel']}%");

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
        return $this->vehicle->with('brand', 'category', 'color', 'type', 'model')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->vehicle->create($data);
    }

    public function update(array $data, string $id)
    {
        $vehicle = $this->vehicle->findOrFail($id);

        $vehicle->update($data);

        return response()->json([
            'message' => 'success'
        ], 204);
    }

    public function delete(string $id)
    {
        $vehicle = $this->vehicle->findOrFail($id);

        $vehicle->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
