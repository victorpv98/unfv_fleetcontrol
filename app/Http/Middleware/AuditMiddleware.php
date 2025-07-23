<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Auditoria;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo auditar operaciones POST, PUT, PATCH, DELETE
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && auth()->check()) {
            $this->logActivity($request);
        }

        return $response;
    }

    private function logActivity(Request $request)
    {
        try {
            Auditoria::create([
                'tabla' => 'actividad_sistema',
                'registro_id' => auth()->id(),
                'accion' => $request->method(),
                'datos_nuevos' => [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'parameters' => $request->except(['password', 'password_confirmation']),
                ],
                'usuario_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Error en auditoría: ' . $e->getMessage());
        }
    }
}