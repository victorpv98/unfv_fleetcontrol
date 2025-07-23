<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autenticado'], 401);
            }
            
            return redirect()->route('login')->with('warning', 'Debe iniciar sesión para acceder al sistema.');
        }

        // Verificar si el usuario está activo
        if (!Auth::user()->activo) {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Usuario desactivado'], 403);
            }
            
            return redirect()->route('login')->with('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
        }

        return $next($request);
    }
}