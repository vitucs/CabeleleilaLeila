<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicosTable extends Migration
{
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('valor', 8, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicos');
    }
}
