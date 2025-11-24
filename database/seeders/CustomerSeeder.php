<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Customer::exists()) {
            Customer::create([
                'login' => 'non-admin',
                'name' => 'Não Administrador',
                'cpf' => '00000000001',
                'email' => 'nonadmin@admin.com',
                'type' => 'customer',
                'address' => fake()->address(),
                'file_id' => null,
                'password' => bcrypt('nonadmin'),
            ]);
        }

        Customer::factory()->count(10)->create();
    }
}
