<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auditoria::with('usuario')->latest();
        
        // Filtrar por acción si se proporciona
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        
        // Filtrar por fecha si se proporciona
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        
        $auditorias = $query->paginate(20);
        
        // Obtener acciones únicas para el filtro
        $acciones = Auditoria::distinct()->pluck('accion');
        
        return view('docente.auditorias.index', compact('auditorias', 'acciones'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Auditoria $auditoria)
    {
        return view('docente.auditorias.show', compact('auditoria'));
    }
}
