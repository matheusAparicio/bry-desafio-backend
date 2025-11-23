<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Employee::exists()) {
            Employee::create([
                'login' => 'admin',
                'name' => 'Administrador',
                'cpf' => '00000000000',
                'email' => 'admin@admin.com',
                'type' => 'employee',
                'address' => fake()->address(),
                'file_id' => null,
                'password' => bcrypt('admin'),
            ]);
        }

        Employee::factory()->count(10)->create();
    }
}
