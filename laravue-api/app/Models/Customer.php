<?php

namespace App\Models;

use App\Traits\HasBelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasUuids, HasBelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'people',
        'document',
        'phone',
        'cellphone',
        'cep',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'people' => 'hashed',
            'active' => 'boolean'
        ];
    }
}
