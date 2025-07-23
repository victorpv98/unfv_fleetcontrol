@extends('layouts.app')

@section('title', 'Control de Movimientos')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-route mr-2"></i>Control de Movimientos
                    </h1>
                    <p class="text-muted mb-0">Registro y seguimiento de movimientos vehiculares</p>
                </div>
                <div>
                    <a href="{{ route('movimientos.activos') }}" class="btn btn-info mr-2">
                        <i class="fas fa-eye mr-2"></i>Ver Activos
                    </a>
                    <a href="{{ route('movimientos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Nuevo Movimiento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-road fa-2x text-primary mb-2"></i>
                    <h4 class="font-weight-bold">{{ $movimientos->where('estado', 'en_curso')->count() }}</h4>
                    <small class="text-muted">En Curso</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="font-weight-bold">{{ $movimientos->where('estado', 'finalizado')->count() }}</h4>
                    <small class="text-muted">Finalizados</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                    <h4 class="font-weight-bold">{{ $movimientos->filter(function($mov) { return $mov->fecha_hora_salida->isToday(); })->count() }}</h4>
                    <small class="text-muted">Hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-week fa-2x text-warning mb-2"></i>
                    <h4 class="font-weight-bold">{{ $movimientos->filter(function($mov) { return $mov->fecha_hora_salida->isCurrentWeek(); })->count() }}</h4>
                    <small class="text-muted">Esta Semana</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="en_curso" {{ request('estado') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="vehiculo_id" class="form-control">
                        <option value="">Todos los vehículos</option>
                        @foreach($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->id }}" {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                {{ $vehiculo->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="fecha_fin" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0">
                <i class="fas fa-list mr-2"></i>Historial de Movimientos ({{ $movimientos->total() }})
            </h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Formulario</th>
                            <th class="border-0">Vehículo</th>
                            <th class="border-0">Conductor</th>
                            <th class="border-0">Destino</th>
                            <th class="border-0">Salida</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Duración</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                        <tr>
                            <td class="align-middle">
                                <small class="font-weight-bold text-primary">{{ $movimiento->formulario_ma122 }}</small>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-car mr-2 text-primary"></i>
                                    <div>
                                        <strong>{{ $movimiento->vehiculo->placa }}</strong>
                                        <small class="d-block text-muted">{{ $movimiento->vehiculo->marca }} {{ $movimiento->vehiculo->modelo }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user mr-2 text-success"></i>
                                    <strong>{{ $movimiento->conductor->nombre_completo }}</strong>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $movimiento->destino->nombre }}</strong>
                                    <small class="d-block text-muted">{{ $movimiento->destino->distrito }}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $movimiento->fecha_hora_salida->format('d/m/Y') }}</strong>
                                <small class="d-block text-muted">{{ $movimiento->fecha_hora_salida->format('H:i') }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $movimiento->estado_badge }} badge-pill">
                                    @if($movimiento->estado === 'en_curso')
                                        <i class="fas fa-circle fa-xs mr-1"></i>En Curso
                                    @elseif($movimiento->estado === 'finalizado')
                                        <i class="fas fa-check fa-xs mr-1"></i>Finalizado
                                    @else
                                        <i class="fas fa-times fa-xs mr-1"></i>Cancelado
                                    @endif
                                </span>
                            </td>
                            <td class="align-middle">
                                @if($movimiento->estado === 'en_curso')
                                    <span class="text-info">{{ $movimiento->tiempo_transcurrido }}</span>
                                @elseif($movimiento->duracion)
                                    <span class="text-muted">{{ $movimiento->duracion }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('movimientos.show', $movimiento) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($movimiento->estado === 'en_curso')
                                        <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#finalizarModal{{ $movimiento->id }}" title="Finalizar">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('movimientos.imprimir-ma122', $movimiento) }}" target="_blank">
                                                <i class="fas fa-print mr-2"></i>Imprimir MA-122
                                            </a>
                                            @if($movimiento->estado === 'en_curso')
                                                <a class="dropdown-item" href="{{ route('movimientos.edit', $movimiento) }}">
                                                    <i class="fas fa-edit mr-2"></i>Editar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal para finalizar movimiento -->
                        @if($movimiento->estado === 'en_curso')
                        <div class="modal fade" id="finalizarModal{{ $movimiento->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Finalizar Movimiento</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('movimientos.finalizar', $movimiento) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha y Hora de Entrada</label>
                                                        <input type="datetime-local" class="form-control" name="fecha_hora_entrada" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Kilometraje de Entrada</label>
                                                        <input type="number" class="form-control" name="kilometraje_entrada" min="{{ $movimiento->kilometraje_salida }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Combustible Final</label>
                                                <input type="number" class="form-control" name="combustible_final" step="0.01" min="0" max="{{ $movimiento->vehiculo->capacidad_tanque }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Observaciones de Entrada</label>
                                                <textarea class="form-control" name="observaciones_entrada" rows="3" placeholder="Observaciones del retorno..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Finalizar Movimiento</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-route fa-3x mb-3 d-block"></i>
                                <h5>No hay movimientos registrados</h5>
                                <p>Comienza registrando el primer movimiento vehicular</p>
                                <a href="{{ route('movimientos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Nuevo Movimiento
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($movimientos->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $movimientos->firstItem() }} a {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} movimientos
                </div>
                <div>
                    {{ $movimientos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh para movimientos en curso cada 30 segundos
    @if(request('estado') === 'en_curso' || !request('estado'))
    setInterval(function() {
        if (!$('.modal').hasClass('show')) {
            location.reload();
        }
    }, 30000);
    @endif
});
</script>
@endpush