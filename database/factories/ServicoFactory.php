<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicoFactory extends Factory
{
    protected $model = Servico::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'valor' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->word,
        ];
    }
}
