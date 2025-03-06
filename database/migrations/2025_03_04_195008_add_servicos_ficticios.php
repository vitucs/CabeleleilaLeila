<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddServicosFicticios extends Migration
{
    public function up()
    {
        DB::table('servicos')->insert([
            [
                'nome' => 'Corte de Cabelo Feminino',
                'valor' => 50.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Corte de Cabelo Masculino',
                'valor' => 40.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Pintura de Cabelo',
                'valor' => 120.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Manicure',
                'valor' => 30.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Pedicure',
                'valor' => 35.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Limpeza de Pele',
                'valor' => 70.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Massagem Relaxante',
                'valor' => 90.00,
                'status' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        DB::table('servicos')->truncate();
    }
}
