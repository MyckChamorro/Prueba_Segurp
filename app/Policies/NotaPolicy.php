<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Nota;
use App\Models\User;

class NotaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('docente');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Nota $nota): bool
    {
        // Los docentes pueden ver notas de sus asignaturas
        if ($user->hasRole('docente')) {
            return $user->asignaturas()->where('asignaturas.id', $nota->asignatura_id)->exists();
        }
        
        // Los estudiantes solo pueden ver sus propias notas
        if ($user->hasRole('estudiante')) {
            return $nota->estudiante_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('docente');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Nota $nota): bool
    {
        // Solo docentes que tienen la asignatura pueden editar
        return $user->hasRole('docente') && 
               $user->asignaturas()->where('asignaturas.id', $nota->asignatura_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Nota $nota): bool
    {
        // Solo docentes que tienen la asignatura pueden eliminar
        return $user->hasRole('docente') && 
               $user->asignaturas()->where('asignaturas.id', $nota->asignatura_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Nota $nota): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Nota $nota): bool
    {
        return false;
    }
}
