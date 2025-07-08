<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('asignatura_id')->constrained()->onDelete('cascade');
            $table->decimal('nota_1', 4, 2)->nullable();
            $table->decimal('nota_2', 4, 2)->nullable();
            $table->decimal('nota_3', 4, 2)->nullable();
            $table->decimal('promedio', 4, 2)->nullable();
            $table->enum('estado_final', ['aprobado', 'reprobado', 'pendiente'])->default('pendiente');
            $table->timestamps();
            
            $table->unique(['estudiante_id', 'asignatura_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
