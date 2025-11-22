<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'customer';
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('customer', function ($query) {
            $query->where('type', 'customer');
        });
    }
}
