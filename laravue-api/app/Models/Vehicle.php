<?php

namespace App\Models;

use App\Enums\VehicleFuelEnum;
use App\Traits\HasBelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends BaseModel
{
    use HasFactory, HasUuids, SoftDeletes, HasBelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'category_id',
        'color_id',
        'type_id',
        'model_id',
        'plate',
        'fuel',
        'description',
        'year',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'fuel'   => VehicleFuelEnum::class,
            'year'   => 'date',
            'active' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }


    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }


    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }
}
