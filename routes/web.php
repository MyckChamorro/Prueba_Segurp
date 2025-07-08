<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rutas protegidas para docentes
Route::middleware(['auth', 'verified', 'role:docente'])->group(function () {
    Route::prefix('docente')->name('docente.')->group(function () {
        // Gestión de asignaturas
        Route::resource('asignaturas', \App\Http\Controllers\AsignaturaController::class);
        
        // Gestión de notas
        Route::resource('notas', \App\Http\Controllers\NotaController::class);
        
        // Rutas adicionales para notas
        Route::get('asignaturas/{asignatura}/estudiantes', [\App\Http\Controllers\AsignaturaController::class, 'estudiantes'])->name('asignaturas.estudiantes');
        Route::post('asignaturas/{asignatura}/estudiantes', [\App\Http\Controllers\AsignaturaController::class, 'asignarEstudiante'])->name('asignaturas.asignar-estudiante');
        
        // Rutas para obtener estudiantes por AJAX
        Route::get('asignaturas/{asignatura}/estudiantes-json', [\App\Http\Controllers\AsignaturaController::class, 'estudiantesJson'])->name('asignaturas.estudiantes.json');
        
        // Auditorías
        Route::get('auditorias', [\App\Http\Controllers\AuditoriaController::class, 'index'])->name('auditorias.index');
    });
});

// Rutas protegidas para estudiantes
Route::middleware(['auth', 'verified', 'role:estudiante'])->group(function () {
    Route::prefix('estudiante')->name('estudiante.')->group(function () {
        // Ver mis notas
        Route::get('notas', [\App\Http\Controllers\NotaController::class, 'misNotas'])->name('notas.index');
        
        // Ver mis asignaturas
        Route::get('asignaturas', [\App\Http\Controllers\AsignaturaController::class, 'misAsignaturas'])->name('asignaturas.index');
    });
});

// Rutas accesibles para ambos roles
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mi-perfil', [ProfileController::class, 'show'])->name('perfil.show');
});
