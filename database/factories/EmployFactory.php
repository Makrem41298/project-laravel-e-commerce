<?php

namespace Database\Factories;

use App\Models\Employ;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployFactory extends Factory
{
    protected $model = Employ::class;

    public function definition(): array
    {
        return [

            'name' => fake()->name(),
            'email' => 'makrem05@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
