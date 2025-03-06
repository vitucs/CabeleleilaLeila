<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::table('status')->insert([
            ['nome' => 'pendente'],
            ['nome' => 'em_andamento'],
            ['nome' => 'concluido']
        ]);
    }

    public function down()
    {
        DB::table('status')->whereIn('nome', ['pendente', 'em_andamento', 'concluido'])->delete();
    }
};
