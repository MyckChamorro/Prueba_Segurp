<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignatura $asignatura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignatura $asignatura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignatura $asignatura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        //
    }

    /**
     * Mostrar las asignaturas del estudiante autenticado
     */
    public function misAsignaturas()
    {
        $estudiante = auth()->user();
        $asignaturas = $estudiante->asignaturas()->get();
        
        return view('estudiante.asignaturas.index', compact('asignaturas'));
    }

    /**
     * Mostrar estudiantes de una asignatura (para docentes)
     */
    public function estudiantes(Asignatura $asignatura)
    {
        $this->authorize('view', $asignatura);
        $estudiantes = $asignatura->estudiantes()->get();
        
        return view('docente.asignaturas.estudiantes', compact('asignatura', 'estudiantes'));
    }

    /**
     * Asignar estudiante a una asignatura
     */
    public function asignarEstudiante(Request $request, Asignatura $asignatura)
    {
        $this->authorize('update', $asignatura);
        
        $request->validate([
            'estudiante_id' => 'required|exists:users,id'
        ]);
        
        $asignatura->docentes()->attach($request->estudiante_id);
        
        return redirect()->back()->with('success', 'Estudiante asignado correctamente');
    }

    /**
     * Obtener estudiantes de una asignatura en formato JSON
     */
    public function estudiantesJson(Asignatura $asignatura)
    {
        $this->authorize('view', $asignatura);
        
        // Obtener estudiantes que están en el sistema con rol estudiante
        $estudiantes = User::role('estudiante')
            ->activos()
            ->select('id', 'name', 'email')
            ->get();
            
        return response()->json($estudiantes);
    }
}
