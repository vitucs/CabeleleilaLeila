<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendamentoFactory extends Factory
{
    protected $model = Agendamento::class;

    public function definition()
    {
        return [
            'cliente_id' => Cliente::factory(),
            'funcionario_id' => Funcionario::factory(),
            'dataHora' => $this->faker->dateTimeBetween('now', '+1 week'),
            'duracao' => 60,
            'confirmado' => $this->faker->boolean,
        ];
    }
}
