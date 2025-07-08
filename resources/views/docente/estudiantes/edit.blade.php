@extends('layouts.main')

@section('title', 'Editar Estudiante')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Editar Estudiante: {{ $estudiante->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('docente.estudiantes.update', $estudiante) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $estudiante->name) }}" 
                                           required 
                                           maxlength="255"
                                           placeholder="Nombre completo del estudiante">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo_estudiante" class="form-label">Código de Estudiante</label>
                                    <input type="text" 
                                           class="form-control @error('codigo_estudiante') is-invalid @enderror" 
                                           id="codigo_estudiante" 
                                           name="codigo_estudiante" 
                                           value="{{ old('codigo_estudiante', $estudiante->codigo_estudiante) }}" 
                                           maxlength="20"
                                           placeholder="Ej: EST2025001">
                                    @error('codigo_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Código único del estudiante (opcional)</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $estudiante->email) }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="correo@ejemplo.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="activo" {{ old('estado', $estudiante->estado) == 'activo' ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado', $estudiante->estado) == 'inactivo' ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="motivo-container" style="display: {{ old('estado', $estudiante->estado) == 'inactivo' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="motivo_inactivo" class="form-label">Motivo de Inactivación <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('motivo_inactivo') is-invalid @enderror" 
                                           id="motivo_inactivo" 
                                           name="motivo_inactivo" 
                                           value="{{ old('motivo_inactivo', $estudiante->motivo_inactivo) }}" 
                                           placeholder="Motivo por el cual se inactiva">
                                    @error('motivo_inactivo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('docente.estudiantes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Estudiante
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Información del Registro</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Registrado:</dt>
                        <dd class="col-sm-9">{{ $estudiante->created_at->format('d/m/Y H:i') }}</dd>
                        
                        <dt class="col-sm-3">Última actualización:</dt>
                        <dd class="col-sm-9">{{ $estudiante->updated_at->format('d/m/Y H:i') }}</dd>
                        
                        <dt class="col-sm-3">Asignaturas:</dt>
                        <dd class="col-sm-9">{{ $estudiante->asignaturas->count() }} asignaturas inscritas</dd>
                        
                        <dt class="col-sm-3">Notas:</dt>
                        <dd class="col-sm-9">{{ $estudiante->notas->count() }} notas registradas</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Mostrar/ocultar campo de motivo según el estado
    document.getElementById('estado').addEventListener('change', function() {
        const motivoContainer = document.getElementById('motivo-container');
        const motivoInput = document.getElementById('motivo_inactivo');
        
        if (this.value === 'inactivo') {
            motivoContainer.style.display = 'block';
            motivoInput.required = true;
        } else {
            motivoContainer.style.display = 'none';
            motivoInput.required = false;
            motivoInput.value = '';
        }
    });
</script>
@endsection
