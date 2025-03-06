<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('funcionario_id')->constrained('funcionarios');
            $table->dateTime('dataHora');
            $table->integer('duracao');
            $table->boolean('confirmado')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendamentos');
    }
}
