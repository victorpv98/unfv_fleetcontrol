@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Logo UNFV -->
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary mb-1">Sistema de Control de Flotas Vehiculares</h2>
                            <p class="text-muted small">Peru</p>
                        </div>

                        <!-- Mensajes de Estado -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">{{ __('Correo Electrónico') }}</label>
                                <div class="input-group">
                                    <input id="email" 
                                           type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="email"
                                           placeholder="usuario@eps.gob.pe">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">{{ __('Contraseña') }}</label>
                                <div class="input-group">
                                    <input id="password" 
                                           type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           placeholder="Ingrese su contraseña">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input id="remember" 
                                       type="checkbox" 
                                       class="form-check-input" 
                                       name="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="form-check-label">
                                    {{ __('Recordar sesión') }}
                                </label>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    {{ __('Iniciar Sesión') }}
                                </button>
                            </div>

                            <!-- Forgot Password Link -->
                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1"></i>
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Footer del Card -->
                    <div class="card-footer bg-light text-center py-3 border-0">
                        <small class="text-muted">
                            {{ date('Y') }} Universidad Nacional Federico Villarreal
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection