@extends('layouts.app')

@section('title', 'Gestión de Conductores')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-users mr-2"></i>Gestión de Conductores
                    </h1>
                    <p class="text-muted mb-0">Administra los conductores autorizados</p>
                </div>
                <a href="{{ route('conductores.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>Nuevo Conductor
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, DNI...">
                </div>
                <div class="col-md-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="suspendido" {{ request('estado') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="categoria" class="form-control">
                        <option value="">Todas las categorías</option>
                        <option value="A-I" {{ request('categoria') == 'A-I' ? 'selected' : '' }}>A-I</option>
                        <option value="A-IIa" {{ request('categoria') == 'A-IIa' ? 'selected' : '' }}>A-IIa</option>
                        <option value="A-IIb" {{ request('categoria') == 'A-IIb' ? 'selected' : '' }}>A-IIb</option>
                        <option value="A-IIIa" {{ request('categoria') == 'A-IIIa' ? 'selected' : '' }}>A-IIIa</option>
                        <option value="A-IIIb" {{ request('categoria') == 'A-IIIb' ? 'selected' : '' }}>A-IIIb</option>
                        <option value="A-IIIc" {{ request('categoria') == 'A-IIIc' ? 'selected' : '' }}>A-IIIc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('conductores.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Conductores -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0">
                <i class="fas fa-list mr-2"></i>Listado de Conductores ({{ $conductores->total() }})
            </h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Conductor</th>
                            <th class="border-0">DNI</th>
                            <th class="border-0">Licencia</th>
                            <th class="border-0">Vehículo Asignado</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductores as $conductor)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $conductor->nombre_completo }}</strong>
                                        <small class="d-block text-muted">{{ $conductor->edad }} años</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">{{ $conductor->dni }}</td>
                            <td class="align-middle">
                                <strong>{{ $conductor->licencia_categoria }}</strong>
                                <small class="d-block text-muted">
                                    Vence: {{ $conductor->licencia_vencimiento->format('d/m/Y') }}
                                    @if($conductor->dias_para_vencimiento_licencia <= 30)
                                        <span class="badge badge-warning badge-sm ml-1">Próximo a vencer</span>
                                    @endif
                                </small>
                            </td>
                            <td class="align-middle">
                                @if($conductor->asignacionActiva)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-car mr-2 text-primary"></i>
                                        <strong>{{ $conductor->asignacionActiva->vehiculo->placa }}</strong>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-car mr-1"></i>Sin asignar
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $conductor->estado_badge }} badge-pill">
                                    {{ ucfirst($conductor->estado) }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('conductores.show', $conductor) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('conductores.edit', $conductor) }}" class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('conductores.historial', $conductor) }}">
                                                <i class="fas fa-history mr-2"></i>Historial
                                            </a>
                                            <form action="{{ route('conductores.toggle-estado', $conductor) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-toggle-{{ $conductor->estado === 'activo' ? 'off' : 'on' }} mr-2"></i>
                                                    {{ $conductor->estado === 'activo' ? 'Suspender' : 'Activar' }}
                                                </button>
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('conductores.destroy', $conductor) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                <h5>No hay conductores registrados</h5>
                                <p>Comienza agregando el primer conductor</p>
                                <a href="{{ route('conductores.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus mr-2"></i>Agregar Conductor
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($conductores->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $conductores->firstItem() }} a {{ $conductores->lastItem() }} de {{ $conductores->total() }} conductores
                </div>
                <div>
                    {{ $conductores->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection