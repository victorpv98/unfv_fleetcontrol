<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // El AuthMiddleware ya verificó que el usuario esté autenticado y activo
        $user = Auth::user();
        
        // Log para auditoría
        Log::info('Intento de acceso por rol', [
            'user_id' => $user->id,
            'user_role' => $user->rol,
            'required_roles' => $roles,
            'route' => $request->route()->getName(),
            'ip' => $request->ip()
        ]);

        // Verificar si el usuario tiene uno de los roles permitidos
        if (in_array($user->rol, $roles)) {
            return $next($request);
        }

        // Log acceso denegado
        Log::warning('Acceso denegado por rol', [
            'user_id' => $user->id,
            'user_role' => $user->rol,
            'required_roles' => $roles,
            'route' => $request->route()->getName()
        ]);

        // Si es una petición AJAX/API, devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a esta función.',
                'required_roles' => $roles,
                'user_role' => $user->rol
            ], 403);
        }

        // Redirigir según el rol del usuario
        return $this->redirectByRole($user->rol);
    }

    /**
     * Redirige al usuario a su área correspondiente según su rol
     */
    private function redirectByRole($role)
    {
        $redirects = [
            'administrador' => [
                'route' => 'dashboard',
                'message' => 'Redirigido al panel de administración.'
            ],
            'operador' => [
                'route' => 'vehiculos.index',
                'message' => 'No tienes permisos para esta sección. Redirigido a gestión de vehículos.'
            ],
            'jefe_mantenimiento' => [
                'route' => 'mantenimiento.index',
                'message' => 'No tienes permisos para esta sección. Redirigido a gestión de mantenimiento.'
            ],
            'encargado_garaje' => [
                'route' => 'movimientos.activos',
                'message' => 'No tienes permisos para esta sección. Redirigido a control de movimientos.'
            ],
            'supervisor' => [
                'route' => 'reportes.index',
                'message' => 'No tienes permisos para esta sección. Redirigido a reportes.'
            ],
            'mecanico' => [
                'route' => 'mantenimiento.mis-ordenes',
                'message' => 'No tienes permisos para esta sección. Redirigido a tus órdenes de trabajo.'
            ],
            'conductor' => [
                'route' => 'conductor.mi-perfil',
                'message' => 'No tienes permisos para esta sección. Redirigido a tu perfil.'
            ]
        ];

        $redirect = $redirects[$role] ?? [
            'route' => 'dashboard',
            'message' => 'Rol no reconocido. Contacte al administrador.'
        ];

        return redirect()->route($redirect['route'])->with('warning', $redirect['message']);
    }
}

// Middleware adicional para permisos específicos
class PermissionMiddleware
{
    /**
     * Verifica permisos específicos más allá de roles
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();
        
        // Definir permisos específicos por rol
        $permissions = [
            'gestionar-usuarios' => ['administrador'],
            'exportar-reportes' => ['administrador', 'supervisor'],
            'configurar-sistema' => ['administrador'],
            'aprobar-mantenimiento' => ['administrador', 'jefe_mantenimiento'],
            'validar-inspecciones' => ['administrador', 'operador', 'encargado_garaje'],
            'acceder-auditoria' => ['administrador', 'supervisor'],
            'gestionar-repuestos' => ['administrador', 'jefe_mantenimiento'],
            'finalizar-movimientos' => ['administrador', 'operador'],
        ];

        // Verificar si el rol tiene el permiso
        if (isset($permissions[$permission]) && in_array($user->rol, $permissions[$permission])) {
            return $next($request);
        }

        // Log del intento de acceso sin permisos
        Log::warning('Acceso denegado por permisos específicos', [
            'user_id' => $user->id,
            'user_role' => $user->rol,
            'permission' => $permission,
            'route' => $request->route()->getName()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'No tienes permisos específicos para realizar esta acción.',
                'permission' => $permission
            ], 403);
        }

        return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
    }
}

// Middleware para verificar propietario de recursos
class ResourceOwnerMiddleware
{
    /**
     * Verifica que el usuario solo acceda a sus propios recursos
     */
    public function handle(Request $request, Closure $next, $resourceType = null)
    {
        $user = Auth::user();
        
        // Solo aplicar a conductores y mecánicos
        if (!in_array($user->rol, ['conductor', 'mecanico'])) {
            return $next($request);
        }

        $resourceId = $request->route()->parameter('id') ?? 
                     $request->route()->parameter('movimiento') ?? 
                     $request->route()->parameter('orden');

        switch ($user->rol) {
            case 'conductor':
                return $this->validateConductorAccess($user, $request, $resourceId, $next);
            case 'mecanico':
                return $this->validateMecanicoAccess($user, $request, $resourceId, $next);
        }

        return $next($request);
    }

    private function validateConductorAccess($user, $request, $resourceId, $next)
    {
        $conductor = \App\Models\Conductor::where('user_id', $user->id)->first();
        
        if (!$conductor) {
            return redirect()->route('dashboard')->with('error', 'Perfil de conductor no encontrado.');
        }

        // Verificar acceso a movimientos propios
        if ($request->route()->getName() === 'conductor.mis-viajes' && $resourceId) {
            $movimiento = \App\Models\Movimiento::find($resourceId);
            if ($movimiento && $movimiento->conductor_id !== $conductor->id) {
                Log::warning('Conductor intentó acceder a movimiento ajeno', [
                    'conductor_id' => $conductor->id,
                    'movimiento_id' => $resourceId
                ]);
                return redirect()->route('conductor.mis-viajes')->with('error', 'No puedes acceder a información de otros conductores.');
            }
        }

        return $next($request);
    }

    private function validateMecanicoAccess($user, $request, $resourceId, $next)
    {
        // Verificar que el mecánico solo acceda a sus órdenes asignadas
        if (strpos($request->route()->getName(), 'mantenimiento') !== false && $resourceId) {
            $orden = \App\Models\Mantenimiento::find($resourceId);
            if ($orden && $orden->mecanico_id !== $user->id) {
                Log::warning('Mecánico intentó acceder a orden ajena', [
                    'mecanico_id' => $user->id,
                    'orden_id' => $resourceId
                ]);
                return redirect()->route('mantenimiento.mis-ordenes')->with('error', 'No puedes acceder a órdenes de otros mecánicos.');
            }
        }

        return $next($request);
    }
}