<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Vehiculo, Conductor, MovimientoVehicular, OrdenMantenimiento, DocumentoVehicular, Alerta};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas principales
        $totalVehiculos = Vehiculo::count();
        $vehiculosActivos = Vehiculo::activos()->count();
        $vehiculosEnMovimiento = Vehiculo::whereHas('movimientoActivo')->count();
        $vehiculosDisponibles = Vehiculo::disponibles()->count();
        $totalConductores = Conductor::count();
        $conductoresActivos = Conductor::activos()->count();
        $conductoresEnMovimiento = Conductor::whereHas('movimientoActivo')->count();
        $movimientosHoy = MovimientoVehicular::whereDate('fecha_hora_salida', today())->count();
        $movimientosMes = MovimientoVehicular::whereYear('fecha_hora_salida', date('Y'))->whereMonth('fecha_hora_salida', date('m'))->count();
        $mantenimientosPendientes = OrdenMantenimiento::pendientes()->count();
        $mantenimientosEnProceso = OrdenMantenimiento::enProceso()->count();
        $alertasCriticas = Alerta::where('prioridad', 'alta')->where('estado', 'pendiente')->count();
        $documentosProximosVencer = DocumentoVehicular::porVencer(30)->count();
        $documentosVencidos = DocumentoVehicular::vencidos()->count();

        // Datos para gráficos y resúmenes
        $vehiculosPorTipo = Vehiculo::select('tipo_vehiculo', DB::raw('count(*) as total'))->groupBy('tipo_vehiculo')->pluck('total', 'tipo_vehiculo');
        $consumoCombustibleMes = MovimientoVehicular::where('fecha_hora_salida', '>=', Carbon::now()->subDays(30))->sum('combustible_abastecido');
        $kilometrosMes = MovimientoVehicular::where('fecha_hora_salida', '>=', Carbon::now()->subDays(30))->sum('kilometros_recorridos');
        $eficienciaPromedio = $consumoCombustibleMes > 0 ? round($kilometrosMes / $consumoCombustibleMes, 2) : 0;

        // Datos recientes
        $ultimosMovimientos = MovimientoVehicular::with(['vehiculo', 'conductor', 'destino'])->latest('fecha_hora_salida')->limit(5)->get();
        $alertasRecientes = Alerta::with('entidad')->where('estado', 'pendiente')->latest()->limit(5)->get();
        $ordenesRecientes = OrdenMantenimiento::with(['vehiculo', 'taller'])->whereIn('estado', ['solicitada', 'aprobada', 'en_proceso'])->latest('fecha_solicitud')->limit(5)->get();

        // Movimientos de la semana para gráfico
        $movimientosSemana = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $movimientosSemana[$fecha->format('d/m')] = MovimientoVehicular::whereDate('fecha_hora_salida', $fecha)->count();
        }

        return view('dashboard.index', compact(
            'totalVehiculos', 'vehiculosActivos', 'vehiculosEnMovimiento', 'vehiculosDisponibles',
            'totalConductores', 'conductoresActivos', 'conductoresEnMovimiento',
            'movimientosHoy', 'movimientosMes', 'mantenimientosPendientes', 'mantenimientosEnProceso',
            'alertasCriticas', 'documentosProximosVencer', 'documentosVencidos',
            'vehiculosPorTipo', 'consumoCombustibleMes', 'kilometrosMes', 'eficienciaPromedio',
            'ultimosMovimientos', 'alertasRecientes', 'ordenesRecientes', 'movimientosSemana'
        ));
    }

    public function getWidgetData(Request $request)
    {
        $widget = $request->get('widget');
        
        switch ($widget) {
            case 'movimientos_tiempo_real':
                return response()->json([
                    'movimientos_activos' => MovimientoVehicular::enCurso()->count(),
                    'vehiculos_disponibles' => Vehiculo::disponibles()->count()
                ]);
                
            case 'alertas_criticas':
                return response()->json(Alerta::where('prioridad', 'alta')->where('estado', 'pendiente')->with('entidad')->limit(3)->get());
                
            case 'consumo_hoy':
                return response()->json(['consumo' => round(MovimientoVehicular::whereDate('fecha_hora_salida', today())->sum('combustible_abastecido'), 2)]);
                
            default:
                return response()->json(['error' => 'Widget no encontrado'], 404);
        }
    }

    public function getEstadisticasPorFecha(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        
        $movimientos = MovimientoVehicular::whereBetween('fecha_hora_salida', [$fechaInicio, $fechaFin])
            ->selectRaw('COUNT(*) as total_movimientos, SUM(kilometros_recorridos) as total_kilometros, SUM(combustible_abastecido) as total_combustible, AVG(kilometros_recorridos) as promedio_km_por_viaje')
            ->first();

        return response()->json($movimientos);
    }

    public function getChartData($tipo)
    {
        switch ($tipo) {
            case 'movimientos_semana':
                $datos = [];
                for ($i = 6; $i >= 0; $i--) {
                    $fecha = Carbon::now()->subDays($i);
                    $datos[$fecha->format('d/m')] = MovimientoVehicular::whereDate('fecha_hora_salida', $fecha)->count();
                }
                return response()->json($datos);
                
            case 'vehiculos_tipo':
                return response()->json(Vehiculo::select('tipo_vehiculo', DB::raw('count(*) as total'))->groupBy('tipo_vehiculo')->pluck('total', 'tipo_vehiculo'));
                
            case 'mantenimiento_mes':
                return response()->json(OrdenMantenimiento::selectRaw('DAY(fecha_solicitud) as dia, COUNT(*) as total')->whereMonth('fecha_solicitud', date('m'))->groupBy('dia')->pluck('total', 'dia'));
                
            default:
                return response()->json(['error' => 'Tipo de gráfico no válido'], 404);
        }
    }
}