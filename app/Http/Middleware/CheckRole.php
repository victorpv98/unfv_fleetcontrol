<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login')->with('error', 'Debe iniciar sesión para acceder');
        }

        $user = auth()->user();
        
        // Verificar si el usuario está activo
        if (!$user->activo) {
            auth()->logout();
            return redirect('login')->with('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
        }

        // Verificar si tiene el rol requerido
        if (!in_array($user->rol, $roles)) {
            abort(403, 'No tiene permisos para acceder a esta sección. Rol requerido: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}