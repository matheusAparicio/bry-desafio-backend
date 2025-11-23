<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    public function employees()
    {
        return $this->users()->where('type', 'employee');
    }

    public function customers()
    {
        return $this->users()->where('type', 'customer');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'address',
        'cnpj',
    ];
}
