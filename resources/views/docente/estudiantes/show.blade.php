@extends('layouts.main')

@section('title', 'Detalle del Estudiante')

@section('content')
<div class="container">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $estudiante->name }}</h1>
            <p class="text-muted mb-0">
                {{ $estudiante->codigo_estudiante ? 'Código: ' . $estudiante->codigo_estudiante : 'Sin código asignado' }}
            </p>
        </div>
        <div>
            <a href="{{ route('docente.estudiantes.edit', $estudiante) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('docente.estudiantes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información del estudiante -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar bg-primary text-white rounded-circle mx-auto" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                            {{ strtoupper(substr($estudiante->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <dl class="row">
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8">{{ $estudiante->name }}</dd>
                        
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">{{ $estudiante->email }}</dd>
                        
                        <dt class="col-sm-4">Código:</dt>
                        <dd class="col-sm-8">{{ $estudiante->codigo_estudiante ?: 'No asignado' }}</dd>
                        
                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $estudiante->estado === 'activo' ? 'success' : 'danger' }}">
                                {{ ucfirst($estudiante->estado) }}
                            </span>
                        </dd>
                        
                        @if($estudiante->estado === 'inactivo' && $estudiante->motivo_inactivo)
                            <dt class="col-sm-4">Motivo:</dt>
                            <dd class="col-sm-8">{{ $estudiante->motivo_inactivo }}</dd>
                        @endif
                        
                        <dt class="col-sm-4">Registro:</dt>
                        <dd class="col-sm-8">{{ $estudiante->created_at->format('d/m/Y') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-primary">{{ $estudiante->asignaturas->count() }}</h3>
                            <small class="text-muted">Asignaturas</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success">{{ $estudiante->notas->count() }}</h3>
                            <small class="text-muted">Notas</small>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-12">
                            @php
                                $promedio = $estudiante->notas->avg('nota');
                            @endphp
                            <h3 class="text-{{ $promedio ? ($promedio >= 3.0 ? 'success' : 'warning') : 'muted' }}">
                                {{ $promedio ? number_format($promedio, 1) : '--' }}
                            </h3>
                            <small class="text-muted">Promedio General</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asignaturas y notas -->
        <div class="col-md-8">
            <!-- Asignaturas inscritas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Asignaturas Inscritas ({{ $estudiante->asignaturas->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($estudiante->asignaturas->count() > 0)
                        <div class="row">
                            @foreach($estudiante->asignaturas as $asignatura)
                                @php
                                    $notasAsignatura = $estudiante->notas->where('asignatura_id', $asignatura->id);
                                    $promedioAsignatura = $notasAsignatura->avg('nota');
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-primary">{{ $asignatura->codigo }}</h6>
                                            <p class="card-text small">{{ $asignatura->nombre }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-info">{{ $asignatura->creditos }} créditos</span>
                                                @if($promedioAsignatura)
                                                    <span class="badge bg-{{ $promedioAsignatura >= 3.0 ? 'success' : 'warning' }}">
                                                        {{ number_format($promedioAsignatura, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Sin notas</span>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $notasAsignatura->count() }} nota(s)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No tiene asignaturas asignadas</h6>
                            <p class="text-muted">Puede asignar asignaturas desde la gestión de asignaturas</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de notas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Historial de Notas ({{ $estudiante->notas->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($estudiante->notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Asignatura</th>
                                        <th>Tipo</th>
                                        <th>Nota</th>
                                        <th>Peso</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estudiante->notas->sortByDesc('created_at') as $nota)
                                        <tr>
                                            <td>
                                                <strong>{{ $nota->asignatura->codigo }}</strong><br>
                                                <small class="text-muted">{{ $nota->asignatura->nombre }}</small>
                                            </td>
                                            <td>{{ $nota->tipo_evaluacion }}</td>
                                            <td>
                                                <span class="badge bg-{{ $nota->nota >= 3.0 ? 'success' : 'danger' }}">
                                                    {{ number_format($nota->nota, 1) }}
                                                </span>
                                            </td>
                                            <td>{{ $nota->peso }}%</td>
                                            <td>{{ $nota->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No tiene notas registradas</h6>
                            <p class="text-muted">Las notas aparecerán aquí cuando sean registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
