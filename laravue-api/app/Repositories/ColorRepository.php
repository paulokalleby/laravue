<?php

namespace App\Repositories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Builder;

class ColorRepository
{
    protected $color;

    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    public function getAll(array $filters = [])
    {
        $query =  $this->color->when($filters, function (Builder $query) use ($filters) {

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
        return $this->color->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->color->create($data);
    }

    public function update(array $data, string $id)
    {
        $color = $this->color->findOrFail($id);

        $color->update($data);

        return response()->json([
            'message' => 'success'
        ], 204);
    }

    public function delete(string $id)
    {
        $color = $this->color->findOrFail($id);

        $color->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getColorsHasVehicles()
    {
        return $this->color->whereHas('vehicles')->get();
    }
}
