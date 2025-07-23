@extends('layouts.app')
@section('title', 'Gestión de Usuarios')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-user-cog mr-2"></i>Gestión de Usuarios</h1>
                    <p class="text-muted mb-0">Administración de usuarios del sistema</p>
                </div>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="fas fa-user-plus mr-2"></i>Nuevo Usuario</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Usuario</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Rol</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Último Acceso</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $usuario->nombre_completo }}</strong>
                                        <small class="d-block text-muted">Creado: {{ $usuario->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">{{ $usuario->email }}</td>
                            <td class="align-middle">
                                <span class="badge badge-primary badge-pill">{{ ucwords(str_replace('_', ' ', $usuario->rol)) }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $usuario->activo ? 'success' : 'danger' }} badge-pill">
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted">{{ $usuario->updated_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('usuarios.toggle-estado', $usuario) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $usuario->activo ? 'danger' : 'success' }}">
                                                <i class="fas fa-toggle-{{ $usuario->activo ? 'off' : 'on' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4"><i class="fas fa-users fa-3x mb-3 d-block text-muted"></i><h5>No hay usuarios registrados</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($usuarios->hasPages())
        <div class="card-footer bg-white">{{ $usuarios->links() }}</div>
        @endif
    </div>
</div>
@endsection