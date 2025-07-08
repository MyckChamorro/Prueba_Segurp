@extends('layouts.main')

@section('title', 'Editar Nota')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil"></i> Editar Nota
                </h5>
            </div>
            <div class="card-body">
                <!-- Información del Estudiante y Asignatura -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="bi bi-person"></i> Estudiante
                                </h6>
                                <p class="card-text">
                                    <strong>{{ $nota->estudiante->name }}</strong><br>
                                    <small class="text-muted">{{ $nota->estudiante->email }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="bi bi-book"></i> Asignatura
                                </h6>
                                <p class="card-text">
                                    <strong>{{ $nota->asignatura->nombre }}</strong><br>
                                    <small class="text-muted">{{ $nota->asignatura->codigo }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Edición -->
                <form method="POST" action="{{ route('docente.notas.update', $nota) }}">
                    @csrf
                    @method('PUT')

                    <!-- Sección de Notas Actuales -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="bi bi-clipboard-check"></i> Calificaciones Actuales
                            </h6>
                            <div class="alert alert-info">
                                <strong>Valores actuales:</strong> 
                                Nota 1: {{ $nota->nota_1 ?? 'No registrada' }} | 
                                Nota 2: {{ $nota->nota_2 ?? 'No registrada' }} | 
                                Nota 3: {{ $nota->nota_3 ?? 'No registrada' }} | 
                                Promedio: {{ $nota->promedio ? number_format($nota->promedio, 2) : 'No calculado' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_1" class="form-label">Nueva Nota 1</label>
                            <input type="number" class="form-control @error('nota_1') is-invalid @enderror" 
                                   name="nota_1" id="nota_1" value="{{ old('nota_1', $nota->nota_1) }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_2" class="form-label">Nueva Nota 2</label>
                            <input type="number" class="form-control @error('nota_2') is-invalid @enderror" 
                                   name="nota_2" id="nota_2" value="{{ old('nota_2', $nota->nota_2) }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_3" class="form-label">Nueva Nota 3</label>
                            <input type="number" class="form-control @error('nota_3') is-invalid @enderror" 
                                   name="nota_3" id="nota_3" value="{{ old('nota_3', $nota->nota_3) }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview del Nuevo Promedio -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Nuevo Promedio</h5>
                                            <h3 id="promedioPreview" class="text-muted">{{ $nota->promedio ? number_format($nota->promedio, 2) : '-' }}</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Nuevo Estado</h5>
                                            <h3 id="estadoPreview">
                                                <span class="badge bg-{{ $nota->color_estado }}">{{ ucfirst($nota->estado_final) }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motivo Obligatorio -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Auditoría requerida:</strong> Debe proporcionar un motivo para esta modificación.
                            </div>
                            <label for="motivo" class="form-label">
                                <i class="bi bi-file-text"></i> Motivo de la modificación *
                            </label>
                            <textarea class="form-control @error('motivo') is-invalid @enderror" 
                                      name="motivo" id="motivo" rows="4" 
                                      placeholder="Explique detalladamente el motivo de la modificación de esta nota..."
                                      required minlength="10" maxlength="500">{{ old('motivo') }}</textarea>
                            <div class="form-text">
                                Mínimo 10 caracteres, máximo 500. Este motivo quedará registrado en el historial de auditoría.
                            </div>
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Botones -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('docente.notas.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver
                                </a>
                                <div>
                                    <a href="{{ route('docente.notas.show', $nota) }}" class="btn btn-info me-2">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save"></i> Actualizar Nota
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nota1 = document.getElementById('nota_1');
    const nota2 = document.getElementById('nota_2');
    const nota3 = document.getElementById('nota_3');
    const promedioPreview = document.getElementById('promedioPreview');
    const estadoPreview = document.getElementById('estadoPreview');

    function calcularPromedio() {
        const notas = [
            parseFloat(nota1.value) || null,
            parseFloat(nota2.value) || null,
            parseFloat(nota3.value) || null
        ].filter(nota => nota !== null);

        if (notas.length === 0) {
            promedioPreview.textContent = '-';
            estadoPreview.innerHTML = '<span class="badge bg-secondary">Pendiente</span>';
            return;
        }

        const promedio = notas.reduce((sum, nota) => sum + nota, 0) / notas.length;
        promedioPreview.textContent = promedio.toFixed(2);

        if (promedio >= 14.5) {
            promedioPreview.className = 'text-success';
            estadoPreview.innerHTML = '<span class="badge bg-success">Aprobado</span>';
        } else {
            promedioPreview.className = 'text-danger';
            estadoPreview.innerHTML = '<span class="badge bg-danger">Reprobado</span>';
        }
    }

    // Calcular al cargar la página
    calcularPromedio();

    // Calcular cuando cambien los valores
    nota1.addEventListener('input', calcularPromedio);
    nota2.addEventListener('input', calcularPromedio);
    nota3.addEventListener('input', calcularPromedio);
});
</script>
@endpush
