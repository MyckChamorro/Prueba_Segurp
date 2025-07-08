<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario docente
        $docente = User::create([
            'name' => 'Profesor Juan Pérez',
            'email' => 'docente@universidad.com',
            'password' => Hash::make('password123'),
            'estado' => 'activo',
        ]);

        // Asignar rol de docente
        $docente->assignRole('docente');

        // Crear usuario estudiante de ejemplo
        $estudiante = User::create([
            'name' => 'María González',
            'email' => 'estudiante@universidad.com',
            'password' => Hash::make('password123'),
            'estado' => 'activo',
        ]);

        // Asignar rol de estudiante
        $estudiante->assignRole('estudiante');

        // Crear asignaturas
        $asignaturas = [
            [
                'nombre' => 'Programación Avanzada',
                'codigo' => 'PROG001',
            ],
            [
                'nombre' => 'Base de Datos',
                'codigo' => 'BD001',
            ],
            [
                'nombre' => 'Ingeniería de Software',
                'codigo' => 'IS001',
            ],
            [
                'nombre' => 'Matemáticas Discretas',
                'codigo' => 'MAT001',
            ],
        ];

        foreach ($asignaturas as $asignaturaData) {
            $asignatura = Asignatura::create($asignaturaData);
            // Asignar el docente a la asignatura
            $asignatura->docentes()->attach($docente->id);
        }
    }
}
