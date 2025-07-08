@extends('layouts.main')

@section('title', 'Registrar Nueva Nota')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Registrar Nueva Nota
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('docente.notas.store') }}">
                    @csrf
                    
                    <div class="row">
                        <!-- Selección de Asignatura -->
                        <div class="col-md-6 mb-3">
                            <label for="asignatura_id" class="form-label">
                                <i class="bi bi-book"></i> Asignatura *
                            </label>
                            <select class="form-select @error('asignatura_id') is-invalid @enderror" 
                                    name="asignatura_id" id="asignatura_id" required>
                                <option value="">Seleccionar asignatura...</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}" 
                                            {{ old('asignatura_id') == $asignatura->id ? 'selected' : '' }}>
                                        {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('asignatura_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección de Estudiante -->
                        <div class="col-md-6 mb-3">
                            <label for="estudiante_id" class="form-label">
                                <i class="bi bi-person"></i> Estudiante *
                            </label>
                            <select class="form-select @error('estudiante_id') is-invalid @enderror" 
                                    name="estudiante_id" id="estudiante_id" required>
                                <option value="">Seleccionar estudiante...</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" 
                                            {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->name }} ({{ $estudiante->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('estudiante_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Sección de Notas -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">
                                <i class="bi bi-clipboard-check"></i> Calificaciones (0 - 20 puntos)
                            </h6>
                            <small class="text-muted">
                                Ingrese las notas. El promedio se calculará automáticamente. 
                                Nota mínima de aprobación: 14.5
                            </small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_1" class="form-label">Nota 1</label>
                            <input type="number" class="form-control @error('nota_1') is-invalid @enderror" 
                                   name="nota_1" id="nota_1" value="{{ old('nota_1') }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_2" class="form-label">Nota 2</label>
                            <input type="number" class="form-control @error('nota_2') is-invalid @enderror" 
                                   name="nota_2" id="nota_2" value="{{ old('nota_2') }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nota_3" class="form-label">Nota 3</label>
                            <input type="number" class="form-control @error('nota_3') is-invalid @enderror" 
                                   name="nota_3" id="nota_3" value="{{ old('nota_3') }}" 
                                   min="0" max="20" step="0.01" placeholder="0.00">
                            @error('nota_3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview del Promedio -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Promedio Calculado</h5>
                                            <h3 id="promedioPreview" class="text-muted">-</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Estado</h5>
                                            <h3 id="estadoPreview">
                                                <span class="badge bg-secondary">Pendiente</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Registrar Nota
                                </button>
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

    nota1.addEventListener('input', calcularPromedio);
    nota2.addEventListener('input', calcularPromedio);
    nota3.addEventListener('input', calcularPromedio);
});
</script>
@endpush
