<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Asignatura;
use App\Models\User;
use App\Models\Auditoria;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotaRequest;
use App\Http\Requests\UpdateNotaRequest;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    /**
     * Mostrar las notas del estudiante autenticado
     */
    public function misNotas()
    {
        $estudiante = auth()->user();
        $notas = $estudiante->notas()->with('asignatura')->get();
        
        return view('estudiante.notas.index', compact('notas'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docente = auth()->user();
        
        // Obtener asignaturas del docente
        $asignaturas = $docente->asignaturas()->with(['notas.estudiante'])->get();
        
        return view('docente.notas.index', compact('asignaturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docente = auth()->user();
        $asignaturas = $docente->asignaturas()->get();
        $estudiantes = User::role('estudiante')->activos()->get();
        
        return view('docente.notas.create', compact('asignaturas', 'estudiantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotaRequest $request)
    {
        // Verificar si ya existe una nota para este estudiante y asignatura
        $notaExistente = Nota::where('estudiante_id', $request->estudiante_id)
            ->where('asignatura_id', $request->asignatura_id)
            ->first();
            
        if ($notaExistente) {
            return redirect()->back()
                ->withErrors(['estudiante_id' => 'Ya existe una nota registrada para este estudiante en la asignatura seleccionada.'])
                ->withInput();
        }
        
        $nota = Nota::create($request->validated());
        
        return redirect()->route('docente.notas.index')
            ->with('success', 'Nota creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nota $nota)
    {
        $this->authorize('view', $nota);
        
        return view('docente.notas.show', compact('nota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nota $nota)
    {
        $this->authorize('update', $nota);
        
        return view('docente.notas.edit', compact('nota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotaRequest $request, Nota $nota)
    {
        $this->authorize('update', $nota);
        
        // Guardar valores anteriores para auditoría
        $valoresAnteriores = [
            'nota_1' => $nota->nota_1,
            'nota_2' => $nota->nota_2,
            'nota_3' => $nota->nota_3,
            'promedio' => $nota->promedio,
            'estado_final' => $nota->estado_final,
        ];
        
        // Actualizar la nota
        $nota->update($request->except('motivo'));
        
        // Registrar auditoría
        $motivo = $request->motivo . " | Valores anteriores: " . json_encode($valoresAnteriores);
        
        Auditoria::create([
            'usuario_id' => auth()->id(),
            'accion' => 'editar_nota',
            'motivo' => $motivo,
        ]);
        
        return redirect()->route('docente.notas.index')
            ->with('success', 'Nota actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Nota $nota)
    {
        $this->authorize('delete', $nota);
        
        // Validar que se proporcione un motivo
        $request->validate([
            'motivo' => 'required|string|min:10|max:500'
        ], [
            'motivo.required' => 'Debe proporcionar un motivo para eliminar la nota.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'motivo.max' => 'El motivo no puede exceder 500 caracteres.',
        ]);
        
        // Guardar datos para auditoría antes de eliminar
        $datosNota = [
            'estudiante' => $nota->estudiante->name,
            'asignatura' => $nota->asignatura->nombre,
            'nota_1' => $nota->nota_1,
            'nota_2' => $nota->nota_2,
            'nota_3' => $nota->nota_3,
            'promedio' => $nota->promedio,
            'estado_final' => $nota->estado_final,
        ];
        
        // Eliminar la nota
        $nota->delete();
        
        // Registrar auditoría
        Auditoria::create([
            'usuario_id' => auth()->id(),
            'accion' => 'eliminar_nota',
            'motivo' => $request->motivo . " | Datos eliminados: " . json_encode($datosNota),
        ]);
        
        return redirect()->route('docente.notas.index')
            ->with('success', 'Nota eliminada exitosamente.');
    }
}
