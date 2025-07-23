@extends('layouts.app')
@section('title', 'Centro de Reportes')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-primary font-weight-bold">
                <i class="fas fa-chart-bar mr-2"></i>Centro de Reportes
            </h1>
            <p class="text-muted mb-0">Informes y análisis de la gestión vehicular</p>
        </div>
    </div>

    <!-- Reportes de Flota -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-car fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Reporte de Flota</h5>
                    <p class="card-text text-muted">Estado general de todos los vehículos, asignaciones y documentos</p>
                    <a href="{{ route('reportes.flota') }}" class="btn btn-primary">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-primary ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-route fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Reporte de Movimientos</h5>
                    <p class="card-text text-muted">Análisis de rutas, kilometrajes y tiempos de viaje</p>
                    <a href="{{ route('reportes.movimientos') }}" class="btn btn-info">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-info ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-wrench fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">Reporte de Mantenimiento</h5>
                    <p class="card-text text-muted">Órdenes de trabajo, costos y programación de servicios</p>
                    <a href="{{ route('reportes.mantenimiento') }}" class="btn btn-warning">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-warning ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes de Costos -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-gas-pump fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Reporte de Combustible</h5>
                    <p class="card-text text-muted">Consumo, eficiencia y costos de combustible por vehículo</p>
                    <a href="{{ route('reportes.combustible') }}" class="btn btn-success">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-success ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title">Reporte de Costos</h5>
                    <p class="card-text text-muted">Análisis de costos operativos y de mantenimiento</p>
                    <a href="{{ route('reportes.costos') }}" class="btn btn-danger">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-danger ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-file-alt fa-3x text-secondary"></i>
                    </div>
                    <h5 class="card-title">Reporte de Documentos</h5>
                    <p class="card-text text-muted">Estado de documentos vehiculares y vencimientos</p>
                    <a href="{{ route('reportes.documentos') }}" class="btn btn-secondary">
                        <i class="fas fa-eye mr-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Generador de Reportes Personalizados -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="card-title mb-0">
                <i class="fas fa-cogs mr-2"></i>Generador de Reportes Personalizados
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('reportes.exportar-pdf') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tipo de Reporte</label>
                            <select class="form-control" name="tipo_reporte" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="flota">Flota Vehicular</option>
                                <option value="movimientos">Movimientos</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="combustible">Combustible</option>
                                <option value="costos">Costos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Formato</label>
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-outline-primary" name="formato" value="pdf">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </button>
                                <button type="submit" class="btn btn-outline-success" name="formato" value="excel" formaction="{{ route('reportes.exportar-excel') }}">
                                    <i class="fas fa-file-excel mr-1"></i>Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ App\Models\Vehiculo::count() }}</h3>
                    <small class="text-muted">Total Vehículos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ App\Models\MovimientoVehicular::whereMonth('created_at', date('m'))->count() }}</h3>
                    <small class="text-muted">Movimientos Este Mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ App\Models\OrdenMantenimiento::whereMonth('created_at', date('m'))->count() }}</h3>
                    <small class="text-muted">Mantenimientos Este Mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-success">S/ {{ number_format(App\Models\OrdenMantenimiento::whereMonth('created_at', date('m'))->sum('costo_real'), 2) }}</h3>
                    <small class="text-muted">Costos Este Mes</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection