<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddFuncionariosFicticios extends Migration
{
    public function up()
    {
        DB::table('funcionarios')->insert([
            [
                'nome' => 'João',
                'sobrenome' => 'Silva',
                'email' => 'joao@exemplo.com',
                'password' => Hash::make('senha123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Maria',
                'sobrenome' => 'Santos',
                'email' => 'maria@exemplo.com',
                'password' => Hash::make('senha123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        DB::table('funcionarios')->whereIn('email', ['joao@exemplo.com', 'maria@exemplo.com'])->delete();
    }
}
