<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuncionarioAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('funcionario')->check()) {
            return redirect()->route('funcionario.dashboard');
        }
        return view('auth.funcionarios.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('funcionario')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('funcionario/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('funcionario')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
