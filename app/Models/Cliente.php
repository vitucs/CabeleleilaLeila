<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'password',
    ];
    
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
