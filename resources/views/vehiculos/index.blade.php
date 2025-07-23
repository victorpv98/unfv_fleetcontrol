@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-car mr-2"></i>Gestión de Vehículos
                    </h1>
                    <p class="text-muted mb-0">Administra la flota vehicular de la EPS</p>
                </div>
                <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Nuevo Vehículo
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por placa o marca...">
                </div>
                <div class="col-md-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <option value="automovil" {{ request('tipo') == 'automovil' ? 'selected' : '' }}>Automóvil</option>
                        <option value="camioneta" {{ request('tipo') == 'camioneta' ? 'selected' : '' }}>Camioneta</option>
                        <option value="camion" {{ request('tipo') == 'camion' ? 'selected' : '' }}>Camión</option>
                        <option value="bus" {{ request('tipo') == 'bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Vehículos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>Listado de Vehículos ({{ $vehiculos->total() }})
                    </h6>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fas fa-download mr-1"></i>Exportar
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-excel mr-2"></i>Excel
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-pdf mr-2"></i>PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Placa</th>
                            <th class="border-0">Vehículo</th>
                            <th class="border-0">Conductor Asignado</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Kilometraje</th>
                            <th class="border-0">Documentos</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-car fa-sm"></i>
                                    </div>
                                    <strong>{{ $vehiculo->placa }}</strong>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong>
                                    <small class="d-block text-muted">{{ $vehiculo->año }} - {{ ucfirst($vehiculo->tipo_vehiculo) }}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                @if($vehiculo->asignacionActiva)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle mr-2 text-success"></i>
                                        <div>
                                            <strong>{{ $vehiculo->asignacionActiva->conductor->nombre_completo }}</strong>
                                            <small class="d-block text-muted">{{ $vehiculo->asignacionActiva->conductor->licencia_categoria }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user-times mr-1"></i>Sin asignar
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $vehiculo->estado_badge }} badge-pill">
                                    {{ ucfirst($vehiculo->estado) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <strong>{{ number_format($vehiculo->kilometraje_actual) }} km</strong>
                                @if($vehiculo->requiere_mantenimiento)
                                    <br><small class="text-warning">
                                        <i class="fas fa-wrench mr-1"></i>Requiere mantenimiento
                                    </small>
                                @endif
                            </td>
                            <td class="align-middle">
                                @php
                                    $documentosVencidos = $vehiculo->documentos->where('estado_calculado', 'vencido')->count();
                                    $documentosPorVencer = $vehiculo->documentos->where('estado_calculado', 'por_vencer')->count();
                                @endphp
                                @if($documentosVencidos > 0)
                                    <span class="badge badge-danger">{{ $documentosVencidos }} vencidos</span>
                                @elseif($documentosPorVencer > 0)
                                    <span class="badge badge-warning">{{ $documentosPorVencer }} por vencer</span>
                                @else
                                    <span class="badge badge-success">Al día</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('vehiculos.historial', $vehiculo) }}">
                                                <i class="fas fa-history mr-2"></i>Historial
                                            </a>
                                            <a class="dropdown-item" href="{{ route('vehiculos.documentos', $vehiculo) }}">
                                                <i class="fas fa-file-alt mr-2"></i>Documentos
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro?')">
                                                    <i class="fas fa-trash mr-2"></i>Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-car fa-3x mb-3 d-block"></i>
                                <h5>No hay vehículos registrados</h5>
                                <p>Comienza agregando el primer vehículo a la flota</p>
                                <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Agregar Vehículo
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($vehiculos->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $vehiculos->firstItem() }} a {{ $vehiculos->lastItem() }} de {{ $vehiculos->total() }} vehículos
                </div>
                <div>
                    {{ $vehiculos->links() }}
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
});
</script>
@endpush