<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir roles de usuarios existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Corrigiendo roles de usuarios...');

        // Corregir rol del docente
        $docente = User::where('email', 'docente@universidad.com')->first();
        if ($docente) {
            $this->info("Usuario encontrado: {$docente->name}");
            $this->info("Roles actuales: " . $docente->getRoleNames()->implode(', '));
            
            // Remover todos los roles y asignar el correcto
            $docente->syncRoles(['docente']);
            
            $this->info("Rol actualizado a: " . $docente->fresh()->getRoleNames()->implode(', '));
        } else {
            $this->error('Usuario docente no encontrado');
        }

        // Corregir rol del estudiante
        $estudiante = User::where('email', 'estudiante@universidad.com')->first();
        if ($estudiante) {
            $this->info("Usuario encontrado: {$estudiante->name}");
            $this->info("Roles actuales: " . $estudiante->getRoleNames()->implode(', '));
            
            // Asegurar que tiene rol de estudiante
            $estudiante->syncRoles(['estudiante']);
            
            $this->info("Rol confirmado: " . $estudiante->fresh()->getRoleNames()->implode(', '));
        } else {
            $this->error('Usuario estudiante no encontrado');
        }

        $this->info('Corrección de roles completada.');
        
        return 0;
    }
}
