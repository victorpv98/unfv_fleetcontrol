<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['activo' => true] // Solo permitir usuarios activos
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Registrar el acceso en auditoría
        $this->registrarAcceso($user);

        // Mensaje de bienvenida personalizado según el rol
        $mensajes = [
            'administrador' => '¡Bienvenido Administrador! Tienes acceso completo al sistema.',
            'operador' => '¡Bienvenido Operador! Puedes gestionar vehículos y movimientos.',
            'jefe_mantenimiento' => '¡Bienvenido Jefe de Mantenimiento! Gestiona las órdenes de trabajo.',
            'encargado_garaje' => '¡Bienvenido Encargado de Garaje! Controla las entradas y salidas.',
            'supervisor' => '¡Bienvenido Supervisor! Monitorea las operaciones de la flota.',
            'mecanico' => '¡Bienvenido Mecánico! Registra los trabajos de mantenimiento.',
            'conductor' => '¡Bienvenido Conductor! Consulta tu información vehicular.',
        ];

        $mensaje = $mensajes[$user->rol] ?? '¡Bienvenido al Sistema de Control de Flotas!';
        
        return redirect()->intended($this->redirectPath())
                        ->with('success', $mensaje);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Verificar si el usuario existe pero está inactivo
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($user && !$user->activo) {
            throw ValidationException::withMessages([
                $this->username() => ['Su cuenta ha sido desactivada. Contacte al administrador.'],
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Registrar el cierre de sesión en auditoría
        if (Auth::check()) {
            $this->registrarCierreSesion(Auth::user());
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 204)
            : redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Registrar el acceso del usuario en auditoría
     */
    private function registrarAcceso($user)
    {
        try {
            \App\Models\Auditoria::create([
                'usuario_id' => $user->id,
                'accion' => 'login',
                'tabla' => 'users',
                'registro_id' => $user->id,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now(),
                    'rol' => $user->rol,
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Si falla la auditoría, no afectar el login
            \Log::warning('Error al registrar acceso en auditoría: ' . $e->getMessage());
        }
    }

    /**
     * Registrar el cierre de sesión en auditoría
     */
    private function registrarCierreSesion($user)
    {
        try {
            \App\Models\Auditoria::create([
                'usuario_id' => $user->id,
                'accion' => 'logout',
                'tabla' => 'users',
                'registro_id' => $user->id,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now(),
                    'session_duration' => $this->calculateSessionDuration(),
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Si falla la auditoría, no afectar el logout
            \Log::warning('Error al registrar cierre de sesión en auditoría: ' . $e->getMessage());
        }
    }

    /**
     * Calcular duración de la sesión
     */
    private function calculateSessionDuration()
    {
        // Intentar obtener el tiempo de inicio de sesión desde la sesión
        $loginTime = session('login_time', now());
        return now()->diffInMinutes($loginTime);
    }

    /**
     * Handle a successful authentication attempt.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        
        // Guardar tiempo de inicio de sesión
        session(['login_time' => now()]);

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new \Illuminate\Http\JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }
}