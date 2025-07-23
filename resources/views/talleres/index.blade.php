@extends('layouts.app')
@section('title', 'Gestión de Talleres')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-tools mr-2"></i>Gestión de Talleres</h1>
                    <p class="text-muted mb-0">Red de talleres propios y externos</p>
                </div>
                <a href="{{ route('talleres.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Nuevo Taller</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Taller</th>
                            <th class="border-0">Tipo</th>
                            <th class="border-0">Contacto</th>
                            <th class="border-0">Órdenes</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talleres as $taller)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-{{ $taller->tipo === 'propio' ? 'home' : 'building' }}"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $taller->nombre }}</strong>
                                        <small class="d-block text-muted">{{ $taller->direccion }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $taller->tipo_badge }} badge-pill">{{ ucfirst($taller->tipo) }}</span>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $taller->contacto_nombre ?: 'No especificado' }}</strong>
                                    <small class="d-block text-muted">{{ $taller->telefono }} | {{ $taller->email }}</small>
                                </div>
                            </td>
                            <td class="align-middle"><span class="badge badge-secondary">{{ $taller->ordenes_mantenimiento_count }}</span></td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $taller->activo ? 'success' : 'danger' }} badge-pill">
                                    {{ $taller->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('talleres.show', $taller) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('talleres.edit', $taller) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('talleres.toggle-estado', $taller) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $taller->activo ? 'danger' : 'success' }}">
                                            <i class="fas fa-toggle-{{ $taller->activo ? 'off' : 'on' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4"><i class="fas fa-tools fa-3x mb-3 d-block text-muted"></i><h5>No hay talleres registrados</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($talleres->hasPages())
        <div class="card-footer bg-white">{{ $talleres->links() }}</div>
        @endif
    </div>
</div>
@endsection