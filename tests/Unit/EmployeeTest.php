<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_model_returns_only_employees()
    {
        // Create users with different types
        User::factory()->create(['type' => 'employee']);
        User::factory()->create(['type' => 'employee']);
        User::factory()->create(['type' => 'customer']);

        // Employee::all() should return only users with employee type
        $employees = Employee::all();

        $this->assertCount(2, $employees);
        $this->assertTrue($employees->every(fn($e) => $e->type === 'employee'));
    }
}
