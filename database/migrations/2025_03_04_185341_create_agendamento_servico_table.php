<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentoServicoTable extends Migration
{
    public function up()
    {
        Schema::create('agendamento_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agendamento_id')->constrained('agendamentos')->onDelete('cascade');
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['agendamento_id', 'servico_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendamento_servico');
    }
}
