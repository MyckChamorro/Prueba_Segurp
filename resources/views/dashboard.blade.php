@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="bi bi-house-heart"></i> ¡Bienvenido, {{ auth()->user()->name }}!
                </h4>
                <p class="card-text">
                    Tu rol actual es: <strong>{{ auth()->user()->getRoleNames()->first() }}</strong>
                </p>
                <p class="card-text">
                    <small>Estado de cuenta: 
                        <span class="badge {{ auth()->user()->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst(auth()->user()->estado) }}
                        </span>
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @role('docente')
        <!-- Stats para Docente -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ auth()->user()->asignaturas->count() }}</h5>
                            <small>Asignaturas</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-book fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ \App\Models\Nota::whereHas('asignatura', function($q) { $q->whereHas('docentes', function($q2) { $q2->where('users.id', auth()->id()); }); })->count() }}</h5>
                            <small>Notas Registradas</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-clipboard-check fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ \App\Models\Nota::whereHas('asignatura', function($q) { $q->whereHas('docentes', function($q2) { $q2->where('users.id', auth()->id()); }); })->where('estado_final', 'aprobado')->count() }}</h5>
                            <small>Aprobados</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-trophy fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ \App\Models\Auditoria::where('usuario_id', auth()->id())->count() }}</h5>
                            <small>Auditorías</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-file-text fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas para Docente -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-lightning"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('docente.notas.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle"></i> Registrar Nota
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('docente.notas.index') }}" class="btn btn-success w-100">
                                <i class="bi bi-list-check"></i> Ver Notas
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('docente.asignaturas.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-book"></i> Mis Asignaturas
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('docente.auditorias.index') }}" class="btn btn-warning w-100">
                                <i class="bi bi-file-text"></i> Auditorías
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endrole

    @role('estudiante')
        <!-- Stats para Estudiante -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ auth()->user()->asignaturas->count() }}</h5>
                            <small>Asignaturas Inscritas</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-book fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>{{ auth()->user()->notas->where('estado_final', 'aprobado')->count() }}</h5>
                            <small>Asignaturas Aprobadas</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-trophy fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            @php
                                $promedio = auth()->user()->notas->where('promedio', '!=', null)->avg('promedio');
                            @endphp
                            <h5>{{ number_format($promedio, 2) }}</h5>
                            <small>Promedio General</small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-bar-chart fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mis Últimas Notas -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-clipboard-check"></i> Mis Últimas Notas</h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Asignatura</th>
                                        <th>Nota 1</th>
                                        <th>Nota 2</th>
                                        <th>Nota 3</th>
                                        <th>Promedio</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->notas->take(5) as $nota)
                                        <tr>
                                            <td>{{ $nota->asignatura->nombre }}</td>
                                            <td>{{ $nota->nota_1 ?? '-' }}</td>
                                            <td>{{ $nota->nota_2 ?? '-' }}</td>
                                            <td>{{ $nota->nota_3 ?? '-' }}</td>
                                            <td>{{ $nota->promedio ? number_format($nota->promedio, 2) : '-' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $nota->color_estado }}">
                                                    {{ ucfirst($nota->estado_final) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('estudiante.notas.index') }}" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Ver Todas Mis Notas
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center">No tienes notas registradas aún.</p>
                    @endif
                </div>
            </div>
        </div>
    @endrole
</div>
@endsection
