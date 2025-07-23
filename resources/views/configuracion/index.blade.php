@extends('layouts.app')
@section('title', 'Configuración del Sistema')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-primary font-weight-bold">
                <i class="fas fa-cog mr-2"></i>Configuración del Sistema
            </h1>
            <p class="text-muted mb-0">Parámetros y configuraciones generales</p>
        </div>
    </div>

    <div class="row">
        <!-- Configuración General -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-sliders-h mr-2"></i>Configuración General</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.general') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre del Sistema</label>
                            <input type="text" class="form-control" name="nombre_sistema" 
                                   value="{{ $configuraciones['nombre_sistema'] ?? 'Sistema de Control de Flotas' }}">
                        </div>
                        <div class="form-group">
                            <label>Email de Notificaciones</label>
                            <input type="email" class="form-control" name="email_notificaciones" 
                                   value="{{ $configuraciones['email_notificaciones'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Teléfono de Emergencia</label>
                            <input type="text" class="form-control" name="telefono_emergencia" 
                                   value="{{ $configuraciones['telefono_emergencia'] ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Configuración de Alertas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0"><i class="fas fa-bell mr-2"></i>Configuración de Alertas</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.alertas') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Alerta SOAT (días antes del vencimiento)</label>
                            <input type="number" class="form-control" name="alerta_soat_dias" 
                                   value="{{ $configuraciones['alerta_soat_dias'] ?? 30 }}" min="1" max="365">
                        </div>
                        <div class="form-group">
                            <label>Alerta Revisión Técnica (días antes)</label>
                            <input type="number" class="form-control" name="alerta_revision_dias" 
                                   value="{{ $configuraciones['alerta_revision_dias'] ?? 30 }}" min="1" max="365">
                        </div>
                        <div class="form-group">
                            <label>Alerta Mantenimiento (cada N kilómetros)</label>
                            <input type="number" class="form-control" name="alerta_mantenimiento_km" 
                                   value="{{ $configuraciones['alerta_mantenimiento_km'] ?? 5000 }}" min="1000">
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-2"></i>Guardar Alertas
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Configuración de Mantenimiento -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-wrench mr-2"></i>Configuración de Mantenimiento</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.mantenimiento') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Mantenimiento Preventivo (kilómetros)</label>
                            <input type="number" class="form-control" name="km_mantenimiento_preventivo" 
                                   value="{{ $configuraciones['km_mantenimiento_preventivo'] ?? 10000 }}" min="1000">
                        </div>
                        <div class="form-group">
                            <label>Mantenimiento Preventivo (meses)</label>
                            <input type="number" class="form-control" name="meses_mantenimiento_preventivo" 
                                   value="{{ $configuraciones['meses_mantenimiento_preventivo'] ?? 6 }}" min="1" max="12">
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save mr-2"></i>Guardar Mantenimiento
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Backup y Mantenimiento -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-database mr-2"></i>Backup y Mantenimiento</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Respaldo de Base de Datos</h6>
                        <p class="text-muted small">Genera un respaldo completo del sistema</p>
                        <form action="{{ route('configuracion.backup') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-download mr-2"></i>Generar Backup
                            </button>
                        </form>
                    </div>
                    <hr>
                    <div>
                        <h6>Limpieza de Sistema</h6>
                        <p class="text-muted small">Elimina registros antiguos de auditoría</p>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#limpiezaModal">
                            <i class="fas fa-broom mr-2"></i>Limpiar Sistema
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Sistema -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="card-title mb-0"><i class="fas fa-server mr-2"></i>Estado del Sistema</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <h4 class="text-primary">{{ App\Models\Vehiculo::count() }}</h4>
                    <small class="text-muted">Vehículos Registrados</small>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <h4 class="text-success">{{ App\Models\Conductor::count() }}</h4>
                    <small class="text-muted">Conductores Activos</small>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <h4 class="text-info">{{ App\Models\MovimientoVehicular::whereDate('created_at', today())->count() }}</h4>
                    <small class="text-muted">Movimientos Hoy</small>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <h4 class="text-warning">{{ App\Models\Alerta::where('estado', 'pendiente')->count() }}</h4>
                    <small class="text-muted">Alertas Pendientes</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Limpieza -->
<div class="modal fade" id="limpiezaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Limpieza</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea limpiar los registros antiguos del sistema?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Esta acción eliminará registros de auditoría anteriores a 6 meses.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('auditoria.limpiar') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirmar Limpieza</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection