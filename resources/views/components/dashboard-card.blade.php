<div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm h-100 dashboard-card" data-color="{{ $color }}">
        @if($route)
        <a href="{{ $route }}" class="text-decoration-none">
        @endif
        
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <!-- Título -->
                    <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                        {{ $title }}
                    </div>
                    
                    <!-- Valor principal -->
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ is_numeric($value) ? number_format($value) : $value }}
                    </div>
                    
                    <!-- Subtítulo -->
                    @if($subtitle)
                    <div class="text-xs text-{{ $color }} mt-1">
                        {{ $subtitle }}
                    </div>
                    @endif
                    
                    <!-- Tendencia -->
                    @if($trend && $trendValue)
                    <div class="text-xs text-{{ $trendColor }} mt-1">
                        @if($trend === 'up')
                            <i class="fas fa-arrow-up mr-1"></i>
                        @elseif($trend === 'down')
                            <i class="fas fa-arrow-down mr-1"></i>
                        @else
                            <i class="fas fa-minus mr-1"></i>
                        @endif
                        {{ $trendValue }}
                    </div>
                    @endif
                </div>
                
                <!-- Icono -->
                <div class="col-auto">
                    <i class="fas {{ $icon }} fa-2x text-{{ $color }}"></i>
                </div>
            </div>
        </div>
        
        @if($route)
        </a>
        @endif
    </div>
</div>

<style>
.dashboard-card {
    transition: all 0.3s ease;
    cursor: {{ $route ? 'pointer' : 'default' }};
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.dashboard-card[data-color="primary"] {
    border-left: 4px solid var(--primary-blue);
}

.dashboard-card[data-color="info"] {
    border-left: 4px solid var(--accent-cyan);
}

.dashboard-card[data-color="success"] {
    border-left: 4px solid #28a745;
}

.dashboard-card[data-color="warning"] {
    border-left: 4px solid #ffc107;
}

.dashboard-card[data-color="danger"] {
    border-left: 4px solid #dc3545;
}