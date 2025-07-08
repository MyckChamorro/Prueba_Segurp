<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = User::role('estudiante')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('docente.estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('docente.estudiantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'codigo_estudiante' => ['nullable', 'string', 'max:20', 'unique:users,codigo_estudiante'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'codigo_estudiante' => $request->codigo_estudiante,
            'estado' => 'activo',
        ]);

        // El rol estudiante se asigna automáticamente en el modelo User
        
        return redirect()->route('docente.estudiantes.index')
            ->with('success', 'Estudiante registrado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $estudiante)
    {
        // Verificar que el usuario sea estudiante
        if (!$estudiante->hasRole('estudiante')) {
            abort(404);
        }
        
        $estudiante->load(['asignaturas', 'notas.asignatura']);
        
        return view('docente.estudiantes.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $estudiante)
    {
        // Verificar que el usuario sea estudiante
        if (!$estudiante->hasRole('estudiante')) {
            abort(404);
        }
        
        return view('docente.estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $estudiante)
    {
        // Verificar que el usuario sea estudiante
        if (!$estudiante->hasRole('estudiante')) {
            abort(404);
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$estudiante->id],
            'codigo_estudiante' => ['nullable', 'string', 'max:20', 'unique:users,codigo_estudiante,'.$estudiante->id],
            'estado' => ['required', 'in:activo,inactivo'],
            'motivo_inactivo' => ['required_if:estado,inactivo', 'nullable', 'string'],
        ]);

        $estudiante->update($request->only(['name', 'email', 'codigo_estudiante', 'estado', 'motivo_inactivo']));

        return redirect()->route('docente.estudiantes.index')
            ->with('success', 'Estudiante actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $estudiante)
    {
        // Verificar que el usuario sea estudiante
        if (!$estudiante->hasRole('estudiante')) {
            abort(404);
        }
        
        // Verificar si tiene notas registradas
        if ($estudiante->notas()->count() > 0) {
            return redirect()->route('docente.estudiantes.index')
                ->with('error', 'No se puede eliminar el estudiante porque tiene notas registradas');
        }

        $estudiante->delete();

        return redirect()->route('docente.estudiantes.index')
            ->with('success', 'Estudiante eliminado exitosamente');
    }
}
