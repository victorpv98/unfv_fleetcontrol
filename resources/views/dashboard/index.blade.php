@extends('layouts.app')

@section('title', 'Dashboard - Control de Flotas')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard - Control de Flotas
                    </h1>
                    <p class="text-muted mb-0">Resumen ejecutivo de la gestión vehicular</p>
                </div>
                <div class="text-right">
                    <small class="text-muted">Última actualización:</small><br>
                    <span class="font-weight-bold" id="ultima-actualizacion">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas Principales -->
    <div class="row mb-4">
        <!-- Vehículos Totales -->
        <x-dashboard-card 
            title="Total Vehículos"
            :value="$totalVehiculos"
            :subtitle="$vehiculosActivos . ' activos'"
            icon="fa-car"
            color="primary"
            :route="route('vehiculos.index')"
        />

        <!-- Vehículos en Movimiento -->
        <x-dashboard-card 
            title="En Movimiento"
            :value="$vehiculosEnMovimiento"
            :subtitle="$vehiculosDisponibles . ' disponibles'"
            icon="fa-route"
            color="info"
            :route="route('movimientos.activos')"
        />

        <!-- Conductores Activos -->
        <x-dashboard-card 
            title="Conductores"
            :value="$totalConductores"
            :subtitle="$conductoresActivos . ' habilitados'"
            icon="fa-users"
            color="success"
            :route="route('conductores.index')"
        />

        <!-- Alertas Críticas -->
        <x-dashboard-card 
            title="Alertas Críticas"
            :value="$alertasCriticas"
            :subtitle="$documentosVencidos . ' docs vencidos'"
            icon="fa-exclamation-circle"
            color="danger"
            :route="route('alertas.index')"
        />
    </div>

    <!-- Estadísticas de Rendimiento -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>
                        Movimientos de la Semana
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartMovimientos" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gas-pump mr-2"></i>
                        Consumo del Mes
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="h4 mb-0 font-weight-bold text-info">{{ number_format($consumoCombustibleMes, 1) }}</div>
                    <div class="text-muted">Galones consumidos</div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-xs text-uppercase text-muted mb-1">Kilómetros</div>
                            <div class="h6 mb-0">{{ number_format($kilometrosMes) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-xs text-uppercase text-muted mb-1">Eficiencia</div>
                            <div class="h6 mb-0">{{ $eficienciaPromedio }} km/gal</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-wrench mr-2"></i>
                        Mantenimiento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="text-xs text-uppercase text-muted mb-1">Pendientes</div>
                            <div class="h5 mb-0 text-warning">{{ $mantenimientosPendientes }}</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="text-xs text-uppercase text-muted mb-1">En Proceso</div>
                            <div class="h5 mb-0 text-info">{{ $mantenimientosEnProceso }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Actividad Reciente -->
    <div class="row">
        <!-- Últimos Movimientos -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>
                        Últimos Movimientos
                    </h6>
                    <a href="{{ route('movimientos.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    @forelse($ultimosMovimientos as $movimiento)
                        <div class="d-flex align-items-center px-3 py-3 border-bottom">
                            <div class="mr-3">
                                <span class="badge badge-{{ $movimiento->estado_badge }} badge-pill">
                                    {{ ucfirst($movimiento->estado) }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-dark">{{ $movimiento->vehiculo->placa }}</div>
                                <div class="text-xs text-muted">
                                    {{ $movimiento->conductor->nombre_completo }} → {{ $movimiento->destino->nombre }}
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $movimiento->fecha_hora_salida->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="text-right">
                                @if($movimiento->estado === 'en_curso')
                                    <div class="text-xs text-info">
                                        <i class="fas fa-circle fa-xs mr-1"></i>
                                        En curso
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>No hay movimientos recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Alertas y Notificaciones -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell mr-2"></i>
                        Alertas Recientes
                    </h6>
                    <a href="{{ route('alertas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    @forelse($alertasRecientes as $alerta)
                        <div class="d-flex align-items-start px-3 py-3 border-bottom">
                            <div class="mr-3 mt-1">
                                @if($alerta->prioridad === 'alta')
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                @elseif($alerta->prioridad === 'media')
                                    <i class="fas fa-exclamation-circle text-warning"></i>
                                @else
                                    <i class="fas fa-info-circle text-info"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-dark">{{ $alerta->titulo }}</div>
                                <div class="text-sm text-muted mb-1">{{ Str::limit($alerta->descripcion, 80) }}</div>
                                <div class="text-xs text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $alerta->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p>No hay alertas pendientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de movimientos de la semana
    const ctx = document.getElementById('chartMovimientos').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($movimientosSemana)) !!},
            datasets: [{
                label: 'Movimientos',
                data: {!! json_encode(array_values($movimientosSemana)) !!},
                borderColor: '#00B9F1',
                backgroundColor: 'rgba(0, 185, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06257C',
                pointBorderColor: '#06257C',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Actualización automática cada 5 minutos
    setInterval(function() {
        const now = new Date();
        document.getElementById('ultima-actualizacion').textContent = 
            now.toLocaleDateString('es-PE') + ' ' + now.toLocaleTimeString('es-PE', {
                hour: '2-digit',
                minute: '2-digit'
            });
    }, 300000);
});
</script>
@endpush