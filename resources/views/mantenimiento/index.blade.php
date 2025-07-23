@extends('layouts.app')
@section('title', 'Gestión de Mantenimiento')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-wrench mr-2"></i>Gestión de Mantenimiento</h1>
                    <p class="text-muted mb-0">Órdenes de trabajo y servicios de mantenimiento</p>
                </div>
                <div>
                    <a href="{{ route('mantenimiento.ordenes') }}" class="btn btn-info mr-2">
                        <i class="fas fa-list mr-2"></i>Ver Órdenes
                    </a>
                    <a href="{{ route('mantenimiento.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Nueva Orden
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Mantenimiento -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $ordenes->whereIn('estado', ['solicitada', 'cotizando'])->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-left-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-check fa-2x text-primary"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Aprobadas</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $ordenes->where('estado', 'aprobada')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-left-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-tools fa-2x text-info"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">En Proceso</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $ordenes->where('estado', 'en_proceso')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-left-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Finalizadas</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $ordenes->where('estado', 'finalizada')->count() }}</div>
                        </div>
                    </div>
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
                        <option value="solicitada" {{ request('estado') == 'solicitada' ? 'selected' : '' }}>Solicitada</option>
                        <option value="cotizando" {{ request('estado') == 'cotizando' ? 'selected' : '' }}>Cotizando</option>
                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <option value="preventivo" {{ request('tipo') == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                        <option value="correctivo" {{ request('tipo') == 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                        <option value="emergencia" {{ request('tipo') == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('mantenimiento.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Órdenes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Órdenes de Mantenimiento ({{ $ordenes->total() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">N° Orden</th>
                            <th class="border-0">Vehículo</th>
                            <th class="border-0">Tipo</th>
                            <th class="border-0">Taller</th>
                            <th class="border-0">Fecha</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Prioridad</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordenes as $orden)
                        <tr>
                            <td class="align-middle">
                                <strong class="text-primary">{{ $orden->numero_orden }}</strong>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-car mr-2 text-primary"></i>
                                    <div>
                                        <strong>{{ $orden->vehiculo->placa }}</strong>
                                        <small class="d-block text-muted">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $orden->tipo_mantenimiento === 'preventivo' ? 'info' : ($orden->tipo_mantenimiento === 'correctivo' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($orden->tipo_mantenimiento) }}
                                </span>
                                <small class="d-block text-muted">{{ ucfirst($orden->tipo_servicio) }}</small>
                            </td>
                            <td class="align-middle">
                                @if($orden->taller)
                                    <strong>{{ $orden->taller->nombre }}</strong>
                                    <small class="d-block text-muted">{{ $orden->taller->tipo }}</small>
                                @else
                                    <span class="text-muted">Sin asignar</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <strong>{{ $orden->fecha_solicitud->format('d/m/Y') }}</strong>
                                @if($orden->fecha_programada)
                                    <small class="d-block text-muted">Prog: {{ $orden->fecha_programada->format('d/m') }}</small>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $orden->estado_badge }} badge-pill">
                                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $orden->prioridad_badge }} badge-pill">
                                    {{ ucfirst($orden->prioridad) }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('mantenimiento.show', $orden) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($orden->estado, ['solicitada', 'cotizando']))
                                        <a href="{{ route('mantenimiento.edit', $orden) }}" class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if($orden->estado === 'aprobada')
                                        <button type="button" class="btn btn-sm btn-outline-success" data-toggle="tooltip" title="Iniciar trabajo">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-wrench fa-3x mb-3 d-block text-muted"></i>
                                <h5>No hay órdenes de mantenimiento</h5>
                                <p>Comienza creando la primera orden de trabajo</p>
                                <a href="{{ route('mantenimiento.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Nueva Orden
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ordenes->hasPages())
        <div class="card-footer bg-white">{{ $ordenes->links() }}</div>
        @endif
    </div>
</div>
@endsection