<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;

class CustomerController extends UserController
{
    protected string $model = Customer::class;
}
