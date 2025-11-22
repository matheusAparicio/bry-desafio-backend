<?php

namespace App\Http\Controllers;

use App\Models\User;

class EmployeeController extends UserController
{
    public function index()
    {
        return User::where("type", "employee")->get();
    }
}
