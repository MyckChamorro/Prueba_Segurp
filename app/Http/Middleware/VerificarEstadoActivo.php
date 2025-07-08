<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Auditoria;

class VerificarEstadoActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Verificar si el estado del usuario no es activo
            if ($user->estado !== 'activo') {
                $motivo = $user->motivo_inactivo ?? 'No especificado';
                
                // Registrar auditoría del intento de acceso con cuenta inactiva
                Auditoria::create([
                    'usuario_id' => $user->id,
                    'accion' => 'intento_acceso_cuenta_inactiva',
                    'motivo' => "Usuario intentó acceder con cuenta inactiva. Motivo: {$motivo}",
                ]);
                
                // Cerrar sesión
                Auth::logout();
                
                // Invalidar la sesión
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirigir con mensaje de error
                return redirect('/login')
                    ->withErrors([
                        'email' => "Tu cuenta fue desactivada. Motivo: {$motivo}"
                    ]);
            }
        }
        
        return $next($request);
    }
}
