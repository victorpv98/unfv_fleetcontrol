@extends('layouts.app')
@section('title', 'Gestión de Destinos')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i>Gestión de Destinos</h1>
                    <p class="text-muted mb-0">Ubicaciones y puntos de destino</p>
                </div>
                <a href="{{ route('destinos.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Nuevo Destino</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Destino</th>
                            <th class="border-0">Ubicación</th>
                            <th class="border-0">Dirección</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($destinos as $destino)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px;">
                                        <i class="fas fa-map-pin fa-sm"></i>
                                    </div>
                                    <strong>{{ $destino->nombre }}</strong>
                                </div>
                            </td>
                            <td class="align-middle">{{ $destino->ubicacion_completa }}</td>
                            <td class="align-middle"><small class="text-muted">{{ $destino->direccion }}</small></td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $destino->activo ? 'success' : 'danger' }} badge-pill">
                                    {{ $destino->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('destinos.edit', $destino) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('destinos.toggle-estado', $destino) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $destino->activo ? 'danger' : 'success' }}">
                                            <i class="fas fa-toggle-{{ $destino->activo ? 'off' : 'on' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4"><i class="fas fa-map-marker-alt fa-3x mb-3 d-block text-muted"></i><h5>No hay destinos registrados</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($destinos->hasPages())
        <div class="card-footer bg-white">{{ $destinos->links() }}</div>
        @endif
    </div>
</div>
@endsection