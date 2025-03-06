<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'agendamento_servico', 'agendamento_id', 'servico_id')
                    ->withPivot('status_id')
                    ->withTimestamps();
    }
}
