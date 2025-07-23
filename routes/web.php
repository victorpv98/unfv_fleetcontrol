<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\UsuarioController;


// Redirigir la raíz al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/widget-data', [DashboardController::class, 'getWidgetData'])->name('dashboard.widget-data');
    Route::get('/dashboard/estadisticas', [DashboardController::class, 'getEstadisticasPorFecha'])->name('dashboard.estadisticas');
    
    // Gestión de Vehículos
    Route::resource('vehiculos', VehiculoController::class);
    Route::get('vehiculos/{vehiculo}/historial', [VehiculoController::class, 'historial'])->name('vehiculos.historial');
    Route::get('vehiculos/{vehiculo}/documentos', [VehiculoController::class, 'documentos'])->name('vehiculos.documentos');
    Route::post('vehiculos/{vehiculo}/asignar-conductor', [VehiculoController::class, 'asignarConductor'])->name('vehiculos.asignar-conductor');
    
    // Gestión de Conductores
    Route::resource('conductores', ConductorController::class);
    Route::get('conductores/{conductor}/historial', [ConductorController::class, 'historial'])->name('conductores.historial');
    Route::post('conductores/{conductor}/toggle-estado', [ConductorController::class, 'toggleEstado'])->name('conductores.toggle-estado');
    
    // Gestión de Destinos
    Route::resource('destinos', DestinoController::class);
    Route::post('destinos/{destino}/toggle-estado', [DestinoController::class, 'toggleEstado'])->name('destinos.toggle-estado');
    
    // Gestión de Talleres
    Route::resource('talleres', TallerController::class);
    Route::post('talleres/{taller}/toggle-estado', [TallerController::class, 'toggleEstado'])->name('talleres.toggle-estado');
    
    // Gestión de Repuestos
    Route::resource('repuestos', RepuestoController::class);
    Route::post('repuestos/{repuesto}/ajustar-stock', [RepuestoController::class, 'ajustarStock'])->name('repuestos.ajustar-stock');
    Route::get('repuestos/stock-bajo', [RepuestoController::class, 'stockBajo'])->name('repuestos.stock-bajo');
    
    // Movimientos Vehiculares
    Route::resource('movimientos', MovimientoController::class);
    Route::get('movimientos/activos', [MovimientoController::class, 'activos'])->name('movimientos.activos');
    Route::post('movimientos/{movimiento}/finalizar', [MovimientoController::class, 'finalizar'])->name('movimientos.finalizar');
    Route::get('movimientos/{movimiento}/imprimir-ma122', [MovimientoController::class, 'imprimirMA122'])->name('movimientos.imprimir-ma122');
    
    // Inspecciones vehiculares
    Route::get('movimientos/{movimiento}/inspeccion-salida', [MovimientoController::class, 'inspeccionSalida'])->name('movimientos.inspeccion-salida');
    Route::post('movimientos/{movimiento}/inspeccion-salida', [MovimientoController::class, 'guardarInspeccionSalida'])->name('movimientos.guardar-inspeccion-salida');
    Route::get('movimientos/{movimiento}/inspeccion-entrada', [MovimientoController::class, 'inspeccionEntrada'])->name('movimientos.inspeccion-entrada');
    Route::post('movimientos/{movimiento}/inspeccion-entrada', [MovimientoController::class, 'guardarInspeccionEntrada'])->name('movimientos.guardar-inspeccion-entrada');
    
    // Gestión de Mantenimiento
    Route::resource('mantenimiento', MantenimientoController::class);
    Route::get('mantenimiento/ordenes', [MantenimientoController::class, 'ordenes'])->name('mantenimiento.ordenes');
    Route::get('mantenimiento/cotizaciones', [MantenimientoController::class, 'cotizaciones'])->name('mantenimiento.cotizaciones');
    Route::get('mantenimiento/facturas', [MantenimientoController::class, 'facturas'])->name('mantenimiento.facturas');
    Route::post('mantenimiento/{orden}/aprobar', [MantenimientoController::class, 'aprobar'])->name('mantenimiento.aprobar');
    Route::post('mantenimiento/{orden}/finalizar', [MantenimientoController::class, 'finalizar'])->name('mantenimiento.finalizar');
    
    // Documentos vehiculares
    Route::get('documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('documentos/vencimientos', [DocumentoController::class, 'vencimientos'])->name('documentos.vencimientos');
    Route::post('vehiculos/{vehiculo}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::put('documentos/{documento}', [DocumentoController::class, 'update'])->name('documentos.update');
    Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
    
    // Alertas y Notificaciones
    Route::resource('alertas', AlertaController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('alertas/{alerta}/marcar-leida', [AlertaController::class, 'marcarLeida'])->name('alertas.marcar-leida');
    Route::post('alertas/marcar-todas-leidas', [AlertaController::class, 'marcarTodasLeidas'])->name('alertas.marcar-todas-leidas');
    
    // Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/flota', [ReporteController::class, 'flota'])->name('reportes.flota');
    Route::get('reportes/movimientos', [ReporteController::class, 'movimientos'])->name('reportes.movimientos');
    Route::get('reportes/mantenimiento', [ReporteController::class, 'mantenimiento'])->name('reportes.mantenimiento');
    Route::get('reportes/combustible', [ReporteController::class, 'combustible'])->name('reportes.combustible');
    Route::get('reportes/costos', [ReporteController::class, 'costos'])->name('reportes.costos');
    Route::get('reportes/documentos', [ReporteController::class, 'documentos'])->name('reportes.documentos');
    
    // Exportar reportes
    Route::post('reportes/exportar-pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar-pdf');
    Route::post('reportes/exportar-excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar-excel');
    
    // Configuración del sistema
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('configuracion/general', [ConfiguracionController::class, 'general'])->name('configuracion.general');
    Route::post('configuracion/alertas', [ConfiguracionController::class, 'alertas'])->name('configuracion.alertas');
    Route::post('configuracion/mantenimiento', [ConfiguracionController::class, 'mantenimiento'])->name('configuracion.mantenimiento');
    Route::post('configuracion/backup', [ConfiguracionController::class, 'backup'])->name('configuracion.backup');
    
    // Auditoría
    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('auditoria/{auditoria}', [AuditoriaController::class, 'show'])->name('auditoria.show');
    Route::delete('auditoria/limpiar', [AuditoriaController::class, 'limpiar'])->name('auditoria.limpiar');
    
    // Gestión de usuarios (solo administradores)
    Route::middleware(['can:gestionar-usuarios'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::post('usuarios/{usuario}/toggle-estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.toggle-estado');
        Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');
    });
});

// API Routes para funciones específicas
Route::prefix('api')->middleware(['auth'])->group(function () {
    // Contador de alertas para navbar
    Route::get('alertas/count', [AlertaController::class, 'count'])->name('api.alertas.count');
    
    // Búsqueda de vehículos disponibles
    Route::get('vehiculos/disponibles', [VehiculoController::class, 'disponibles'])->name('api.vehiculos.disponibles');
    
    // Búsqueda de conductores disponibles
    Route::get('conductores/disponibles', [ConductorController::class, 'disponibles'])->name('api.conductores.disponibles');
    
    // Validación de documentos
    Route::post('documentos/validar-placa', [DocumentoController::class, 'validarPlaca'])->name('api.documentos.validar-placa');
    
    // Datos para gráficos
    Route::get('dashboard/chart-data/{tipo}', [DashboardController::class, 'getChartData'])->name('api.dashboard.chart-data');
});

// Rutas de autenticación usando Laravel Auth
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// Ruta adicional para manejar el logout desde el navbar
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
})->name('logout')->middleware('auth');