<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AgendamentoServico extends Pivot
{
    use HasFactory;

    protected $table = 'agendamento_servico';
    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
