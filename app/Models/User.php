<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public function newFromBuilder($attributes = [], $connection = null)
    {
        $attributes = (array) $attributes;

        if (isset($attributes['type'])) {
            $class = match($attributes['type']) {
                'employee' => Employee::class,
                'customer' => Customer::class,
                default    => User::class,
            };
            
            $model = (new $class)->setConnection($connection);
            $model->setRawAttributes($attributes, true);
            $model->exists = true;

            return $model;
        }

        return parent::newFromBuilder($attributes, $connection);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'document_file',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'type'
    ];
}
