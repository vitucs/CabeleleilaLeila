<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;
    protected $table = 'servicos';

    public function agendamentos()
    {
        return $this->belongsToMany(Agendamento::class, 'agendamento_servico', 'servico_id', 'agendamento_id')
                    ->withPivot('status_id')
                    ->withTimestamps();
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
