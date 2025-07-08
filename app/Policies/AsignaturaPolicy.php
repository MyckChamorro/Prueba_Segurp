<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Asignatura;
use App\Models\User;

class AsignaturaPolicy
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
    public function view(User $user, Asignatura $asignatura): bool
    {
        // Los docentes pueden ver asignaturas donde están asignados
        if ($user->hasRole('docente')) {
            return $asignatura->docentes->contains($user->id);
        }
        
        // Los estudiantes pueden ver asignaturas donde están inscritos
        if ($user->hasRole('estudiante')) {
            return $asignatura->estudiantes->contains($user->id);
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
    public function update(User $user, Asignatura $asignatura): bool
    {
        // Solo los docentes asignados a la asignatura pueden editarla
        return $user->hasRole('docente') && $asignatura->docentes->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Asignatura $asignatura): bool
    {
        // Solo los docentes asignados a la asignatura pueden eliminarla
        return $user->hasRole('docente') && $asignatura->docentes->contains($user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Asignatura $asignatura): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Asignatura $asignatura): bool
    {
        return false;
    }
}
