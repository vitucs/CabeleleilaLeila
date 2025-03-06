<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('cliente')->check()) {
            return redirect()->route('cliente.dashboard');
        }
        
        return view('auth.clientes.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('cliente')->check() || Auth::guard('funcionario')->check()) {
            return redirect()->back()->with('error', 'Já existe uma sessão ativa. Por favor, faça logout antes de tentar um novo login.');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('cliente')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('cliente/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
