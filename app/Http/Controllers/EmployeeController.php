<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;

class EmployeeController extends UserController
{
    protected string $model = Employee::class;
}
