@extends('layouts.app')
@section('title', 'Centro de Alertas')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-bell mr-2"></i>Centro de Alertas</h1>
                    <p class="text-muted mb-0">Notificaciones y alertas del sistema</p>
                </div>
                <form action="{{ route('alertas.marcar-todas-leidas') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-check-double mr-2"></i>Marcar Todas como Leídas
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Alertas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>
                    <h4 class="font-weight-bold">{{ $alertas->where('prioridad', 'alta')->where('estado', 'pendiente')->count() }}</h4>
                    <small class="text-muted">Críticas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <h4 class="font-weight-bold">{{ $alertas->where('prioridad', 'media')->where('estado', 'pendiente')->count() }}</h4>
                    <small class="text-muted">Importantes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-info-circle fa-2x text-info mb-2"></i>
                    <h4 class="font-weight-bold">{{ $alertas->where('prioridad', 'baja')->where('estado', 'pendiente')->count() }}</h4>
                    <small class="text-muted">Informativas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="font-weight-bold">{{ $alertas->where('estado', 'leida')->count() }}</h4>
                    <small class="text-muted">Leídas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Alertas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Todas las Alertas ({{ $alertas->total() }})</h6>
        </div>
        <div class="card-body p-0">
            @forelse($alertas as $alerta)
            <div class="d-flex align-items-start p-3 border-bottom {{ $alerta->estado === 'pendiente' ? 'bg-light' : '' }}">
                <div class="mr-3 mt-1">
                    @if($alerta->prioridad === 'alta')
                        <i class="fas fa-exclamation-circle text-danger fa-lg"></i>
                    @elseif($alerta->prioridad === 'media')
                        <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                    @else
                        <i class="fas fa-info-circle text-info fa-lg"></i>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 font-weight-bold">{{ $alerta->titulo }}</h6>
                            <p class="mb-2 text-muted">{{ $alerta->descripcion }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>{{ $alerta->created_at->diffForHumans() }}
                                @if($alerta->entidad)
                                    | <i class="fas fa-link mr-1"></i>{{ class_basename($alerta->entidad_type) }}
                                @endif
                            </small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $alerta->prioridad === 'alta' ? 'danger' : ($alerta->prioridad === 'media' ? 'warning' : 'info') }} mb-2">
                                {{ ucfirst($alerta->prioridad) }}
                            </span>
                            <br>
                            @if($alerta->estado === 'pendiente')
                                <form action="{{ route('alertas.marcar-leida', $alerta) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check mr-1"></i>Marcar Leída
                                    </button>
                                </form>
                            @else
                                <span class="badge badge-success">Leída</span>
                            @endif
                            <a href="{{ route('alertas.show', $alerta) }}" class="btn btn-sm btn-outline-primary ml-1">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <h5>No hay alertas</h5>
                <p class="text-muted">El sistema está funcionando sin alertas pendientes</p>
            </div>
            @endforelse
        </div>
        @if($alertas->hasPages())
        <div class="card-footer bg-white">{{ $alertas->links() }}</div>
        @endif
    </div>
</div>
@endsection