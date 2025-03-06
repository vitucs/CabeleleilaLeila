<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuncionarioAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('funcionario')->check()) {
            return redirect()->route('funcionario.login');
        }

        return $next($request);
    }
}
