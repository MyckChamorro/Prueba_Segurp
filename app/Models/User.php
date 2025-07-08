<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'codigo_estudiante',
        'estado',
        'motivo_inactivo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación many-to-many con Asignatura
     */
    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class);
    }

    /**
     * Relación one-to-many con Nota (como estudiante)
     */
    public function notas()
    {
        return $this->hasMany(Nota::class, 'estudiante_id');
    }

    /**
     * Relación one-to-many con Auditoria
     */
    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'usuario_id');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // Solo registrar auditoría si hay usuario autenticado (creado por docente)
            if (auth()->check()) {
                \App\Models\Auditoria::create([
                    'usuario_id' => auth()->id(),
                    'accion' => 'crear_usuario',
                    'motivo' => "Usuario registrado: {$user->name} ({$user->email})",
                ]);
            }
            
            // No asignar roles automáticamente - se hará manualmente en controlador/seeder
        });
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para usuarios inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }
}
