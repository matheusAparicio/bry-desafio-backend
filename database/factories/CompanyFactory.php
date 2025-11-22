<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cnpj' => $this->generateCnpj(),
            'address' => fake()->address(),
        ];
    }

    private function generateCnpj(): string
    {
        $n1 = random_int(0, 9);
        $n2 = random_int(0, 9);
        $n3 = random_int(0, 9);
        $n4 = random_int(0, 9);
        $n5 = random_int(0, 9);
        $n6 = random_int(0, 9);
        $n7 = random_int(0, 9);
        $n8 = random_int(0, 9);

        $n9  = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;

        $d1 = (
            $n1 * 5 + $n2 * 4 + $n3 * 3 + $n4 * 2
            + $n5 * 9 + $n6 * 8 + $n7 * 7 + $n8 * 6
            + $n9 * 5 + $n10 * 4 + $n11 * 3 + $n12 * 2
        );

        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        $d2 = (
            $n1 * 6 + $n2 * 5 + $n3 * 4 + $n4 * 3
            + $n5 * 2 + $n6 * 9 + $n7 * 8 + $n8 * 7
            + $n9 * 6 + $n10 * 5 + $n11 * 4 + $n12 * 3
            + $d1 * 2
        );

        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        return "{$n1}{$n2}{$n3}{$n4}{$n5}{$n6}{$n7}{$n8}{$n9}{$n10}{$n11}{$n12}{$d1}{$d2}";
    }
}
