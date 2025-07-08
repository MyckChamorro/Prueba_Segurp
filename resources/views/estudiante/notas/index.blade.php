@extends('layouts.main')

@section('title', 'Mis Notas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-clipboard-check"></i> Mis Notas Académicas
            </h1>
            <div class="text-muted">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </div>
        </div>

        @if($notas->count() > 0)
            <!-- Estadísticas Generales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-book fs-1"></i>
                            <h4>{{ $notas->count() }}</h4>
                            <small>Asignaturas Cursadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-trophy fs-1"></i>
                            <h4>{{ $notas->where('estado_final', 'aprobado')->count() }}</h4>
                            <small>Aprobadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-x-circle fs-1"></i>
                            <h4>{{ $notas->where('estado_final', 'reprobado')->count() }}</h4>
                            <small>Reprobadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-bar-chart fs-1"></i>
                            <h4>{{ number_format($notas->where('promedio', '!=', null)->avg('promedio'), 2) }}</h4>
                            <small>Promedio General</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Notas -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i> Detalle de Calificaciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Asignatura</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nota 1</th>
                                    <th class="text-center">Nota 2</th>
                                    <th class="text-center">Nota 3</th>
                                    <th class="text-center">Promedio</th>
                                    <th class="text-center">Estado Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notas as $nota)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-book-half me-2 text-primary"></i>
                                                <div>
                                                    <strong>{{ $nota->asignatura->nombre }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $nota->asignatura->codigo }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($nota->nota_1 !== null)
                                                <div class="text-center">
                                                    <span class="badge {{ $nota->nota_1 >= 14.5 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                        {{ number_format($nota->nota_1, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($nota->nota_2 !== null)
                                                <div class="text-center">
                                                    <span class="badge {{ $nota->nota_2 >= 14.5 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                        {{ number_format($nota->nota_2, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($nota->nota_3 !== null)
                                                <div class="text-center">
                                                    <span class="badge {{ $nota->nota_3 >= 14.5 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                        {{ number_format($nota->nota_3, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($nota->promedio !== null)
                                                <div class="text-center">
                                                    <h5 class="mb-0">
                                                        <span class="badge {{ $nota->promedio >= 14.5 ? 'bg-success' : 'bg-danger' }} fs-5">
                                                            {{ number_format($nota->promedio, 2) }}
                                                        </span>
                                                    </h5>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $nota->color_estado }} fs-6">
                                                @if($nota->estado_final === 'aprobado')
                                                    <i class="bi bi-check-circle"></i> Aprobado
                                                @elseif($nota->estado_final === 'reprobado')
                                                    <i class="bi bi-x-circle"></i> Reprobado
                                                @else
                                                    <i class="bi bi-clock"></i> Pendiente
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> Escala de Calificación</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><span class="badge bg-success me-2">14.5 - 20.0</span> Aprobado</li>
                                <li><span class="badge bg-danger me-2">0.0 - 14.4</span> Reprobado</li>
                                <li><span class="badge bg-secondary me-2">-</span> Pendiente de evaluación</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-calculator"></i> Información de Cálculo</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Promedio:</strong> (Nota 1 + Nota 2 + Nota 3) ÷ 3</p>
                            <p class="mb-1"><strong>Nota mínima:</strong> 14.5 para aprobar</p>
                            <p class="mb-0"><strong>Escala:</strong> 0 a 20 puntos</p>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Estado Sin Notas -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes notas registradas</h4>
                    <p class="text-muted">
                        Aún no se han registrado calificaciones para tus asignaturas.<br>
                        Contacta a tus docentes para más información.
                    </p>
                    <a href="{{ route('estudiante.asignaturas.index') }}" class="btn btn-primary">
                        <i class="bi bi-book"></i> Ver Mis Asignaturas
                    </a>
                </div>
            </div>
        @endif

        <!-- Botón para descargar reporte (futuro) -->
        @if($notas->count() > 0)
            <div class="text-end mt-3">
                <button class="btn btn-outline-primary" disabled>
                    <i class="bi bi-download"></i> Descargar Reporte (Próximamente)
                </button>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.table td {
    vertical-align: middle;
}
.fs-6 {
    font-size: 1rem !important;
}
.fs-5 {
    font-size: 1.25rem !important;
}
</style>
@endpush
