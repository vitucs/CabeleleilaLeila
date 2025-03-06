<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FuncionarioRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.funcionarios.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'sobrenome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:funcionarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'sobrenome.required' => 'O campo sobrenome é obrigatório.',
            'sobrenome.max' => 'O sobrenome não pode ter mais de 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);

        $funcionario = Funcionario::create([
            'nome' => $request->nome,
            'sobrenome' => $request->sobrenome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('funcionario')->login($funcionario);

        return redirect()->route('funcionario.dashboard');
    }
}
