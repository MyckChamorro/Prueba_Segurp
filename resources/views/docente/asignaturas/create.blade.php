@extends('layouts.main')

@section('title', 'Crear Asignatura')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Crear Nueva Asignatura</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('docente.asignaturas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" 
                                           name="codigo" 
                                           value="{{ old('codigo') }}" 
                                           required 
                                           maxlength="20"
                                           placeholder="Ej: MAT101">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Código único de identificación de la asignatura</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="creditos" class="form-label">Créditos <span class="text-danger">*</span></label>
                                    <select class="form-select @error('creditos') is-invalid @enderror" 
                                            id="creditos" 
                                            name="creditos" 
                                            required>
                                        <option value="">Seleccionar créditos</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('creditos') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'crédito' : 'créditos' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('creditos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Asignatura <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="Ej: Matemáticas Básicas">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="4" 
                                      placeholder="Descripción detallada de la asignatura, objetivos, metodología, etc.">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('docente.asignaturas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Asignatura
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Información</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Al crear la asignatura, usted será automáticamente asignado como docente.</li>
                        <li>Podrá asignar estudiantes posteriormente desde la vista de detalle.</li>
                        <li>El código debe ser único en todo el sistema.</li>
                        <li>Los campos marcados con <span class="text-danger">*</span> son obligatorios.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
