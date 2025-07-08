<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AsignaturaController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Asignatura::class, 'asignatura');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Para docentes: ver solo las asignaturas donde están asignados
        if (auth()->user()->hasRole('docente')) {
            $asignaturas = auth()->user()->asignaturas()->with(['docentes', 'estudiantes'])->get();
        } else {
            $asignaturas = Asignatura::with(['docentes', 'estudiantes'])->get();
        }
        
        return view('docente.asignaturas.index', compact('asignaturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('docente.asignaturas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:asignaturas',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        $asignatura = Asignatura::create($request->all());
        
        // Asignar el docente que la creó a la asignatura
        $asignatura->docentes()->attach(auth()->id());

        return redirect()->route('docente.asignaturas.index')
            ->with('success', 'Asignatura creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignatura $asignatura)
    {
        $asignatura->load(['docentes', 'estudiantes', 'notas.estudiante']);
        return view('docente.asignaturas.show', compact('asignatura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignatura $asignatura)
    {
        return view('docente.asignaturas.edit', compact('asignatura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:asignaturas,codigo,' . $asignatura->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        $asignatura->update($request->all());

        return redirect()->route('docente.asignaturas.index')
            ->with('success', 'Asignatura actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        // Verificar si tiene notas registradas
        if ($asignatura->notas()->count() > 0) {
            return redirect()->route('docente.asignaturas.index')
                ->with('error', 'No se puede eliminar la asignatura porque tiene notas registradas');
        }

        $asignatura->delete();

        return redirect()->route('docente.asignaturas.index')
            ->with('success', 'Asignatura eliminada exitosamente');
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
        // $this->authorize('view', $asignatura);
        $estudiantes = $asignatura->estudiantes()->get();
        
        return view('docente.asignaturas.estudiantes', compact('asignatura', 'estudiantes'));
    }

    /**
     * Asignar estudiante a una asignatura
     */
    public function asignarEstudiante(Request $request, Asignatura $asignatura)
    {
        // $this->authorize('update', $asignatura);
        
        $request->validate([
            'estudiante_id' => 'required|exists:users,id'
        ]);
        
        $asignatura->estudiantes()->attach($request->estudiante_id);
        
        return redirect()->back()->with('success', 'Estudiante asignado correctamente');
    }

    /**
     * Obtener estudiantes de una asignatura en formato JSON
     */
    public function estudiantesJson(Asignatura $asignatura)
    {
        // $this->authorize('view', $asignatura);
        
        // Obtener estudiantes que están en el sistema con rol estudiante
        $estudiantes = User::role('estudiante')
            ->activos()
            ->select('id', 'name', 'email')
            ->get();
            
        return response()->json($estudiantes);
    }
}
