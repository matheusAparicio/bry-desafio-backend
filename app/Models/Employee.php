<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'employee';
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('employee', function ($query) {
            $query->where('type', 'employee');
        });
    }
}
