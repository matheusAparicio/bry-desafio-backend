<?php

namespace App\Http\Controllers;

use App\Models\User;

class CustomerController extends UserController
{
    public function index()
    {
        return User::where("type", "customer")->get();
    }
}
