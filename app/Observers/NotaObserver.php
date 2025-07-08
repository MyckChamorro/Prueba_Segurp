<?php

namespace App\Observers;

use App\Models\Nota;
use App\Models\Auditoria;

class NotaObserver
{
    /**
     * Handle the Nota "creating" event.
     */
    public function creating(Nota $nota): void
    {
        $nota->calcularPromedio();
    }

    /**
     * Handle the Nota "created" event.
     */
    public function created(Nota $nota): void
    {
        // Registrar auditoría
        Auditoria::create([
            'usuario_id' => auth()->id(),
            'accion' => 'crear_nota',
            'motivo' => "Nota creada para estudiante ID: {$nota->estudiante_id} en asignatura ID: {$nota->asignatura_id}",
        ]);
    }

    /**
     * Handle the Nota "updating" event.
     */
    public function updating(Nota $nota): void
    {
        $nota->calcularPromedio();
    }

    /**
     * Handle the Nota "updated" event.
     */
    public function updated(Nota $nota): void
    {
        // Registrar auditoría
        if (auth()->check()) {
            Auditoria::create([
                'usuario_id' => auth()->id(),
                'accion' => 'actualizar_nota',
                'motivo' => "Nota actualizada para estudiante ID: {$nota->estudiante_id} en asignatura ID: {$nota->asignatura_id}",
            ]);
        }
    }

    /**
     * Handle the Nota "deleted" event.
     */
    public function deleted(Nota $nota): void
    {
        // Registrar auditoría
        if (auth()->check()) {
            Auditoria::create([
                'usuario_id' => auth()->id(),
                'accion' => 'eliminar_nota',
                'motivo' => "Nota eliminada para estudiante ID: {$nota->estudiante_id} en asignatura ID: {$nota->asignatura_id}",
            ]);
        }
    }

    /**
     * Handle the Nota "restored" event.
     */
    public function restored(Nota $nota): void
    {
        //
    }

    /**
     * Handle the Nota "force deleted" event.
     */
    public function forceDeleted(Nota $nota): void
    {
        //
    }
}
