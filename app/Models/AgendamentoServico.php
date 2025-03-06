<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AgendamentoServico extends Pivot
{
    protected $table = 'agendamento_servico';
    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
