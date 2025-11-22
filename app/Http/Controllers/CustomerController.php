<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends UserController
{
    protected string $model = Customer::class;
}
