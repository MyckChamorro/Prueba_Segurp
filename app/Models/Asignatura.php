<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
    ];

    /**
     * Relación many-to-many con User (docentes)
     */
    public function docentes()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relación one-to-many con Nota
     */
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Obtener estudiantes de la asignatura a través de las notas
     */
    public function estudiantes()
    {
        return $this->hasManyThrough(
            User::class,
            Nota::class,
            'asignatura_id',
            'id',
            'id',
            'estudiante_id'
        );
    }
}
