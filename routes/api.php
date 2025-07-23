<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API para integración externa
Route::middleware('auth:sanctum')->group(function () {
    
    // Información básica del usuario
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // API REST para vehículos
    Route::apiResource('vehiculos', VehiculoController::class);
    Route::get('vehiculos/{vehiculo}/movimientos', [VehiculoController::class, 'movimientos']);
    Route::get('vehiculos/{vehiculo}/mantenimientos', [VehiculoController::class, 'mantenimientos']);
    
    // API REST para movimientos
    Route::apiResource('movimientos', MovimientoController::class);
    Route::post('movimientos/{movimiento}/finalizar', [MovimientoController::class, 'finalizarApi']);
    
    // API REST para mantenimiento
    Route::apiResource('mantenimiento', MantenimientoController::class);
    Route::post('mantenimiento/{orden}/actualizar-estado', [MantenimientoController::class, 'actualizarEstadoApi']);
    
    // Estadísticas y reportes vía API
    Route::get('estadisticas/resumen', [DashboardController::class, 'resumenApi']);
    Route::get('estadisticas/flota', [ReporteController::class, 'flotaApi']);
    Route::get('estadisticas/combustible', [ReporteController::class, 'combustibleApi']);
    
    // Alertas vía API
    Route::get('alertas', [AlertaController::class, 'indexApi']);
    Route::post('alertas/{alerta}/resolver', [AlertaController::class, 'resolverApi']);
});

// API pública (sin autenticación) - solo para consultas básicas
Route::prefix('public')->group(function () {
    Route::get('info', function () {
        return response()->json([
            'sistema' => 'UNFV Fleet Control',
            'version' => '1.0.0',
            'descripcion' => 'Sistema de Control de Flotas Vehiculares para EPS del Perú'
        ]);
    });
    
    // Endpoint para verificar estado del sistema
    Route::get('health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'database' => 'connected'
        ]);
    });
});