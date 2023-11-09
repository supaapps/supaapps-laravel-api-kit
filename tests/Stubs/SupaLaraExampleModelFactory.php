<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupaLaraExampleModelFactory extends Factory
{
    protected $model = SupaLaraExampleModel::class;

    public function definition(): array
    {
        return [
            'label' => fake()->name
        ];
    }
}
