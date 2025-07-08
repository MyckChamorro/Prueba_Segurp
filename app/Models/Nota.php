<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'asignatura_id',
        'nota_1',
        'nota_2',
        'nota_3',
        'promedio',
        'estado_final',
    ];

    protected $casts = [
        'nota_1' => 'decimal:2',
        'nota_2' => 'decimal:2',
        'nota_3' => 'decimal:2',
        'promedio' => 'decimal:2',
    ];

    /**
     * Relación con User (estudiante)
     */
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * Relación con Asignatura
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    /**
     * Calcular el promedio automáticamente
     */
    public function calcularPromedio()
    {
        $notas = collect([$this->nota_1, $this->nota_2, $this->nota_3])
            ->filter(function ($nota) {
                return !is_null($nota) && is_numeric($nota);
            });

        if ($notas->count() > 0) {
            $this->promedio = round($notas->avg(), 2);
            $this->estado_final = $this->promedio >= 14.5 ? 'aprobado' : 'reprobado';
        } else {
            $this->promedio = null;
            $this->estado_final = 'pendiente';
        }
    }

    /**
     * Scope para notas aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado_final', 'aprobado');
    }

    /**
     * Scope para notas reprobadas
     */
    public function scopeReprobadas($query)
    {
        return $query->where('estado_final', 'reprobado');
    }

    /**
     * Scope para notas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado_final', 'pendiente');
    }

    /**
     * Obtener el color del badge según el estado
     */
    public function getColorEstadoAttribute()
    {
        return match($this->estado_final) {
            'aprobado' => 'success',
            'reprobado' => 'danger',
            'pendiente' => 'warning',
            default => 'secondary'
        };
    }
}
