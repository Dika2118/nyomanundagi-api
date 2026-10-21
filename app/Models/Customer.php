<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'email',
        'city',
        'address',
        'client_type',
        'notes',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'customer_id');
    }
}
