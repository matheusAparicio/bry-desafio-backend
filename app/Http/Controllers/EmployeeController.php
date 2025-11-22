<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeeController extends UserController
{
    protected string $model = Employee::class;
}
