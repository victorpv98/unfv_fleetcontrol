<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Vehiculo;

class CheckVehicleStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay un vehículo en la ruta
        if ($request->route('vehiculo')) {
            $vehiculo = $request->route('vehiculo');
            
            // Si es un ID numérico, buscar el vehículo
            if (is_numeric($vehiculo)) {
                $vehiculo = Vehiculo::find($vehiculo);
            }
            
            // Verificar estado del vehículo
            if ($vehiculo) {
                // Operaciones no permitidas en vehículos inactivos
                if ($vehiculo->estado === 'inactivo' && in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
                    if ($request->ajax()) {
                        return response()->json([
                            'error' => 'No se pueden realizar operaciones en vehículos inactivos'
                        ], 422);
                    }
                    
                    return redirect()->back()->with('error', 'No se pueden realizar operaciones en vehículos inactivos');
                }
                
                // Verificar si el vehículo está en mantenimiento para ciertos movimientos
                if ($vehiculo->estado === 'mantenimiento' && str_contains($request->route()->getName(), 'movimientos')) {
                    if ($request->ajax()) {
                        return response()->json([
                            'error' => 'El vehículo está en mantenimiento y no puede realizar movimientos'
                        ], 422);
                    }
                    
                    return redirect()->back()->with('warning', 'El vehículo está en mantenimiento');
                }
            }
        }

        return $next($request);
    }
}