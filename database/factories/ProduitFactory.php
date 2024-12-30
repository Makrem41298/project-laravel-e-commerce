<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            'nomProduit' => $this->faker->word(),
            'categorie_id' => $this->faker->randomNumber(),
            'prix' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
            'quantite' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
