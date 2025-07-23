<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistema de Control de Flotas - EPS')</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #06257C;
            --accent-cyan: #00B9F1;
            --dark-blue: #041C3F;
            --light-gray: #D9D9D6;
            --text-dark: #2C3E50;
            --text-muted: #6C757D;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-dark);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Navbar personalizado */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-dark {
            background-color: var(--dark-blue) !important;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--dark-blue);
            min-height: calc(100vh - 56px);
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.2rem 0.5rem;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(0, 185, 241, 0.1);
            color: var(--accent-cyan);
        }

        .sidebar .nav-link.active {
            background-color: rgba(0, 185, 241, 0.2);
            color: var(--accent-cyan);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.5rem;
        }

        /* Cards personalizadas */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--light-gray);
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        /* Botones */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #04205a;
            border-color: #04205a;
        }

        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        /* Enlaces */
        a {
            color: var(--accent-cyan);
            text-decoration: none;
        }

        a:hover {
            color: #0095c7;
            text-decoration: none;
        }

        /* Títulos */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* Badges */
        .badge-primary {
            background-color: var(--primary-blue);
        }

        .badge-info {
            background-color: var(--accent-cyan);
        }

        /* Utilidades */
        .text-primary {
            color: var(--primary-blue) !important;
        }

        .text-info {
            color: var(--accent-cyan) !important;
        }

        .bg-primary {
            background-color: var(--primary-blue) !important;
        }

        .border-primary {
            border-color: var(--primary-blue) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
        }

        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Status badges */
        .badge-pill {
            border-radius: 50rem;
        }

        /* Chart container */
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar principal -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <!-- Brand/Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fas fa-truck mr-2"></i>
                <span>Control de Flotas EPS</span>
            </a>

            <!-- Toggle button para móvil -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Notificaciones -->
                    <li class="nav-item dropdown">
                        <div class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <h6 class="dropdown-header">Alertas Recientes</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">15 de Julio 2025</div>
                                    <span class="font-weight-bold">SOAT del vehículo ABC-123 por vencer</span>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center small text-gray-500" href="{{ route('alertas.index') }}">
                                Ver todas las alertas
                            </a>
                        </div>
                    </li>

                    <!-- Usuario -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Mi Perfil
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Configuración
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebarMenu">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>

                        <!-- Gestión de Vehículos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}" 
                               href="{{ route('vehiculos.index') }}">
                                <i class="fas fa-car"></i>
                                Vehículos
                            </a>
                        </li>

                        <!-- Conductores -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('conductores.*') ? 'active' : '' }}" 
                               href="{{ route('conductores.index') }}">
                                <i class="fas fa-users"></i>
                                Conductores
                            </a>
                        </li>

                        <!-- Movimientos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }}" 
                               href="{{ route('movimientos.index') }}">
                                <i class="fas fa-route"></i>
                                Movimientos
                            </a>
                        </li>

                        <!-- Mantenimiento -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mantenimiento.*') ? 'active' : '' }}" 
                               href="{{ route('mantenimiento.index') }}">
                                <i class="fas fa-wrench"></i>
                                Mantenimiento
                            </a>
                        </li>

                        <!-- Destinos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('destinos.*') ? 'active' : '' }}" 
                               href="{{ route('destinos.index') }}">
                                <i class="fas fa-map-marker-alt"></i>
                                Destinos
                            </a>
                        </li>

                        <!-- Talleres -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('talleres.*') ? 'active' : '' }}" 
                               href="{{ route('talleres.index') }}">
                                <i class="fas fa-tools"></i>
                                Talleres
                            </a>
                        </li>

                        <!-- Repuestos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('repuestos.*') ? 'active' : '' }}" 
                               href="{{ route('repuestos.index') }}">
                                <i class="fas fa-cogs"></i>
                                Repuestos
                            </a>
                        </li>

                        <hr class="sidebar-divider my-3">

                        <!-- Reportes -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" 
                               href="{{ route('reportes.index') }}">
                                <i class="fas fa-chart-bar"></i>
                                Reportes
                            </a>
                        </li>

                        <!-- Alertas -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alertas.*') ? 'active' : '' }}" 
                               href="{{ route('alertas.index') }}">
                                <i class="fas fa-bell"></i>
                                Alertas
                                @if(isset($alertasPendientes) && $alertasPendientes > 0)
                                    <span class="badge badge-danger badge-pill ml-2">{{ $alertasPendientes }}</span>
                                @endif
                            </a>
                        </li>

                        <hr class="sidebar-divider my-3">

                        <!-- Configuración -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}" 
                               href="{{ route('configuracion.index') }}">
                                <i class="fas fa-cog"></i>
                                Configuración
                            </a>
                        </li>

                        <!-- Auditoría -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}" 
                               href="{{ route('auditoria.index') }}">
                                <i class="fas fa-clipboard-list"></i>
                                Auditoría
                            </a>
                        </li>

                        <!-- Usuarios -->
                        @can('gestionar-usuarios')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" 
                               href="{{ route('usuarios.index') }}">
                                <i class="fas fa-user-cog"></i>
                                Usuarios
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Breadcrumb -->
                @if(View::hasSection('breadcrumb'))
                    <nav aria-label="breadcrumb" class="pt-3">
                        <ol class="breadcrumb bg-transparent p-0">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif

                <!-- Mensajes de alerta -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Contenido de la página -->
                <div class="py-3 fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light border-top mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-2 small">
                        © {{ date('Y') }} Sistema de Control de Flotas Vehiculares para EPS del Perú
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p class="text-muted mb-2 small">
                        Desarrollado por UNFV - FIEI | Versión 1.0
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts personalizados -->
    <script>
        $(document).ready(function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip();

            // Popover initialization
            $('[data-toggle="popover"]').popover();

            // Sidebar toggle para móvil
            $('.navbar-toggler').click(function() {
                $('.sidebar').toggleClass('show');
            });

            // Cerrar sidebar al hacer click fuera de él en móvil
            $(document).click(function(e) {
                if ($(window).width() < 768) {
                    if (!$(e.target).closest('.sidebar, .navbar-toggler').length) {
                        $('.sidebar').removeClass('show');
                    }
                }
            });

            // Confirmar acciones de eliminación
            $('.btn-delete').click(function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.')) {
                    $(this).closest('form').submit();
                }
            });

            // Loading state para botones
            $('.btn-loading').click(function() {
                const $btn = $(this);
                const originalText = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                $btn.prop('disabled', true);
                
                // Restaurar después de 3 segundos si no hay redirección
                setTimeout(function() {
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                }, 3000);
            });
        });

        // Función para actualizar contador de alertas
        function actualizarContadorAlertas() {
            fetch('/api/alertas/count')
                .then(response => response.json())
                .then(data => {
                    const contador = document.getElementById('contador-alertas');
                    if (data.count > 0) {
                        contador.textContent = data.count;
                        contador.style.display = 'inline';
                    } else {
                        contador.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error al actualizar alertas:', error));
        }

        // Actualizar alertas cada 2 minutos
        setInterval(actualizarContadorAlertas, 120000);
    </script>
    
    @stack('scripts')
</body>
</html>