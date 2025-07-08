@extends('layouts.main')

@section('title', 'Registrar Estudiante')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registrar Nuevo Estudiante</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('docente.estudiantes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
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
                                           value="{{ old('codigo_estudiante') }}" 
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
                                   value="{{ old('email') }}" 
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
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    @error('password_confirmation')
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
                                <i class="fas fa-user-plus"></i> Registrar Estudiante
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
                        <li>El estudiante será creado con el rol "estudiante" automáticamente.</li>
                        <li>El estado inicial será "activo".</li>
                        <li>Podrá asignar asignaturas al estudiante después del registro.</li>
                        <li>El código de estudiante es opcional pero debe ser único si se proporciona.</li>
                        <li>Los campos marcados con <span class="text-danger">*</span> son obligatorios.</li>
                        <li>El estudiante podrá cambiar su contraseña después del primer inicio de sesión.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
