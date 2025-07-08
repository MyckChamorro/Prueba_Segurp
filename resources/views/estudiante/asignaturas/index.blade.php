@extends('layouts.main')

@section('title', 'Mis Asignaturas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Mis Asignaturas</h1>
            <p class="text-muted mb-0">Asignaturas en las que estoy inscrito</p>
        </div>
    </div>

    @if($asignaturas->count() > 0)
        <div class="row">
            @foreach($asignaturas as $asignatura)
                @php
                    $misNotas = $asignatura->notas->where('estudiante_id', auth()->id());
                    $promedio = $misNotas->avg('nota');
                    $estadoFinal = $misNotas->first()->estado_final ?? null;
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary fw-bold">{{ $asignatura->codigo }}</h6>
                            <span class="badge bg-info">{{ $asignatura->creditos }} créditos</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $asignatura->nombre }}</h5>
                            
                            @if($asignatura->descripcion)
                                <p class="card-text text-muted small">
                                    {{ Str::limit($asignatura->descripcion, 100) }}
                                </p>
                            @endif

                            <!-- Información de notas -->
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <h4 class="mb-0 text-{{ $promedio ? ($promedio >= 3.0 ? 'success' : 'warning') : 'muted' }}">
                                        {{ $promedio ? number_format($promedio, 1) : '--' }}
                                    </h4>
                                    <small class="text-muted">Promedio</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="mb-0 text-info">{{ $misNotas->count() }}</h4>
                                    <small class="text-muted">Notas</small>
                                </div>
                            </div>

                            @if($estadoFinal)
                                <div class="mt-3">
                                    <span class="badge bg-{{ $estadoFinal === 'Aprobado' ? 'success' : ($estadoFinal === 'Reprobado' ? 'danger' : 'warning') }} w-100">
                                        {{ $estadoFinal }}
                                    </span>
                                </div>
                            @endif

                            <!-- Docentes -->
                            @if($asignatura->docentes->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted">Docentes:</small><br>
                                    @foreach($asignatura->docentes as $docente)
                                        <span class="badge bg-secondary">{{ $docente->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('estudiante.notas.index', ['asignatura_id' => $asignatura->id]) }}" 
                               class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-list"></i> Ver Mis Notas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resumen general -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Resumen Académico</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h3 class="text-primary">{{ $asignaturas->count() }}</h3>
                                <small class="text-muted">Asignaturas Inscritas</small>
                            </div>
                            <div class="col-md-3">
                                <h3 class="text-info">{{ $asignaturas->sum('creditos') }}</h3>
                                <small class="text-muted">Total Créditos</small>
                            </div>
                            <div class="col-md-3">
                                @php
                                    $totalNotas = 0;
                                    $sumaPromedios = 0;
                                    $asignaturasConNotas = 0;
                                    
                                    foreach($asignaturas as $asignatura) {
                                        $notasEstudiante = $asignatura->notas->where('estudiante_id', auth()->id());
                                        $totalNotas += $notasEstudiante->count();
                                        
                                        if($notasEstudiante->count() > 0) {
                                            $sumaPromedios += $notasEstudiante->avg('nota');
                                            $asignaturasConNotas++;
                                        }
                                    }
                                    
                                    $promedioGeneral = $asignaturasConNotas > 0 ? $sumaPromedios / $asignaturasConNotas : 0;
                                @endphp
                                <h3 class="text-{{ $promedioGeneral >= 3.0 ? 'success' : 'warning' }}">
                                    {{ $promedioGeneral > 0 ? number_format($promedioGeneral, 1) : '--' }}
                                </h3>
                                <small class="text-muted">Promedio General</small>
                            </div>
                            <div class="col-md-3">
                                <h3 class="text-warning">{{ $totalNotas }}</h3>
                                <small class="text-muted">Total Notas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No tienes asignaturas asignadas</h4>
                <p class="text-muted">
                    Aún no has sido inscrito en ninguna asignatura. 
                    Contacta con tu coordinador académico para que te asigne las materias correspondientes.
                </p>
                <div class="alert alert-info mt-4">
                    <h6 class="alert-heading">¿Necesitas ayuda?</h6>
                    <p class="mb-0">
                        Si crees que deberías tener asignaturas asignadas, contacta con:
                    </p>
                    <ul class="mt-2 mb-0">
                        <li>Tu coordinador académico</li>
                        <li>El departamento de registro y control</li>
                        <li>Soporte técnico del sistema</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
