<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;

class BrandRepository
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function getAll(array $filters = [])
    {
        $query =  $this->brand->when($filters, function (Builder $query) use ($filters) {

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
        return $this->brand->with('categories')->findOrFail($id);
    }

    public function create(array $data)
    {
        $brand = $this->brand->create($data);

        if (isset($data['categories'])) {

            $brand->categories()->attach(
                array_values($data['categories'])
            );
        }

        return $brand->load('categories');
    }

    public function update(array $data, string $id)
    {
        $brand = $this->brand->findOrFail($id);

        $brand->update($data);

        if (isset($data['categories'])) {

            $brand->categories()->sync(
                array_values($data['categories'])
            );
        }


        return response()->json([
            'message' => 'success'
        ], 204);
    }

    public function delete(string $id)
    {
        $brand = $this->brand->findOrFail($id);

        $brand->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getBrandsHasTypes()
    {
        return $this->brand->whereHas('types')->get();
    }

    public function getBrandsHasVehicles()
    {
        return $this->brand->whereHas('vehicles')->get();
    }
}
