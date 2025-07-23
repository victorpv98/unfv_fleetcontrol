@extends('layouts.app')
@section('title', 'Auditoría del Sistema')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-clipboard-list mr-2"></i>Auditoría del Sistema</h1>
                    <p class="text-muted mb-0">Registro de actividades y cambios en el sistema</p>
                </div>
                <form action="{{ route('auditoria.limpiar') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar registros de auditoría antiguos?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash mr-2"></i>Limpiar Antiguos
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="usuario" class="form-control">
                        <option value="">Todos los usuarios</option>
                        @foreach(App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ request('usuario') == $user->id ? 'selected' : '' }}>
                                {{ $user->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="accion" class="form-control">
                        <option value="">Todas las acciones</option>
                        <option value="login" {{ request('accion') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('accion') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="create" {{ request('accion') == 'create' ? 'selected' : '' }}>Crear</option>
                        <option value="update" {{ request('accion') == 'update' ? 'selected' : '' }}>Actualizar</option>
                        <option value="delete" {{ request('accion') == 'delete' ? 'selected' : '' }}>Eliminar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="fecha" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('auditoria.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Auditoría -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Fecha/Hora</th>
                            <th class="border-0">Usuario</th>
                            <th class="border-0">Acción</th>
                            <th class="border-0">Tabla</th>
                            <th class="border-0">IP</th>
                            <th class="border-0 text-center">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $auditoria)
                        <tr>
                            <td class="align-middle">
                                <strong>{{ $auditoria->created_at->format('d/m/Y') }}</strong>
                                <small class="d-block text-muted">{{ $auditoria->created_at->format('H:i:s') }}</small>
                            </td>
                            <td class="align-middle">
                                @if($auditoria->usuario)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-user fa-xs"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $auditoria->usuario->nombre_completo }}</strong>
                                            <small class="d-block text-muted">{{ $auditoria->usuario->rol }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Usuario eliminado</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ 
                                    $auditoria->accion === 'login' ? 'success' : 
                                    ($auditoria->accion === 'logout' ? 'info' : 
                                    ($auditoria->accion === 'delete' ? 'danger' : 'warning')) 
                                }} badge-pill">
                                    {{ ucfirst($auditoria->accion) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <code>{{ $auditoria->tabla }}</code>
                                @if($auditoria->registro_id)
                                    <small class="d-block text-muted">ID: {{ $auditoria->registro_id }}</small>
                                @endif
                            </td>
                            <td class="align-middle">
                                <small class="text-muted">{{ $auditoria->ip_address }}</small>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('auditoria.show', $auditoria) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-muted"></i>
                                <h5>No hay registros de auditoría</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($auditorias->hasPages())
        <div class="card-footer bg-white">{{ $auditorias->links() }}</div>
        @endif
    </div>
</div>
@endsection