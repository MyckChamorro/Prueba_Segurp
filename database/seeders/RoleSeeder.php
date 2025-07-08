<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $docenteRole = Role::create(['name' => 'docente']);
        $estudianteRole = Role::create(['name' => 'estudiante']);

        // Crear permisos
        $permisos = [
            // Permisos para gestión de notas
            'crear_notas',
            'editar_notas',
            'ver_notas',
            'eliminar_notas',
            
            // Permisos para gestión de asignaturas
            'crear_asignaturas',
            'editar_asignaturas',
            'ver_asignaturas',
            'eliminar_asignaturas',
            
            // Permisos para gestión de usuarios
            'gestionar_usuarios',
            'ver_usuarios',
            
            // Permisos para auditoría
            'ver_auditorias',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignar permisos a roles
        $docenteRole->givePermissionTo([
            'crear_notas',
            'editar_notas',
            'ver_notas',
            'eliminar_notas',
            'ver_asignaturas',
            'gestionar_usuarios',
            'ver_usuarios',
            'ver_auditorias',
        ]);

        $estudianteRole->givePermissionTo([
            'ver_notas',
            'ver_asignaturas',
        ]);
    }
}
