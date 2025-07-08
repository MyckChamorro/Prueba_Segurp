@extends('layouts.main')

@section('title', 'Detalle de Nota')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-eye"></i> Detalle de Nota
            </h1>
            <div>
                <a href="{{ route('docente.notas.edit', $nota) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('docente.notas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Información General -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información General</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <strong>Estudiante:</strong><br>
                                <span class="text-primary">{{ $nota->estudiante->name }}</span><br>
                                <small class="text-muted">{{ $nota->estudiante->email }}</small>
                            </div>
                            <div class="col-6">
                                <strong>Asignatura:</strong><br>
                                <span class="text-primary">{{ $nota->asignatura->nombre }}</span><br>
                                <small class="text-muted">{{ $nota->asignatura->codigo }}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Creado:</strong><br>
                                <small>{{ $nota->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="col-6">
                                <strong>Actualizado:</strong><br>
                                <small>{{ $nota->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calificaciones -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-clipboard-check"></i> Calificaciones</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-3 {{ $nota->nota_1 ? ($nota->nota_1 >= 14.5 ? 'bg-success text-white' : 'bg-danger text-white') : 'bg-light' }}">
                                    <h5 class="mb-0">{{ $nota->nota_1 ?? '-' }}</h5>
                                    <small>Nota 1</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-3 {{ $nota->nota_2 ? ($nota->nota_2 >= 14.5 ? 'bg-success text-white' : 'bg-danger text-white') : 'bg-light' }}">
                                    <h5 class="mb-0">{{ $nota->nota_2 ?? '-' }}</h5>
                                    <small>Nota 2</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-3 {{ $nota->nota_3 ? ($nota->nota_3 >= 14.5 ? 'bg-success text-white' : 'bg-danger text-white') : 'bg-light' }}">
                                    <h5 class="mb-0">{{ $nota->nota_3 ?? '-' }}</h5>
                                    <small>Nota 3</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado Final -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-{{ $nota->color_estado }} text-white">
                        <h6 class="mb-0"><i class="bi bi-calculator"></i> Resultado Final</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <h2 class="text-{{ $nota->promedio >= 14.5 ? 'success' : 'danger' }}">
                                    {{ $nota->promedio ? number_format($nota->promedio, 2) : 'Pendiente' }}
                                </h2>
                                <p class="mb-0"><strong>Promedio Final</strong></p>
                                @if($nota->promedio)
                                    <small class="text-muted">
                                        Calculado: {{ $nota->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h2>
                                    <span class="badge bg-{{ $nota->color_estado }} fs-4">
                                        {{ ucfirst($nota->estado_final) }}
                                    </span>
                                </h2>
                                <p class="mb-0"><strong>Estado Académico</strong></p>
                                <small class="text-muted">
                                    @if($nota->estado_final === 'aprobado')
                                        <i class="bi bi-check-circle text-success"></i> Cumple con la nota mínima (14.5)
                                    @elseif($nota->estado_final === 'reprobado')
                                        <i class="bi bi-x-circle text-danger"></i> No alcanza la nota mínima (14.5)
                                    @else
                                        <i class="bi bi-clock text-warning"></i> Esperando evaluación completa
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Auditorías relacionadas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Cambios</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $auditorias = \App\Models\Auditoria::where('motivo', 'like', '%estudiante ID: ' . $nota->estudiante_id . '%')
                                ->where('motivo', 'like', '%asignatura ID: ' . $nota->asignatura_id . '%')
                                ->latest()
                                ->get();
                        @endphp

                        @if($auditorias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                            <th>Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditorias as $auditoria)
                                            <tr>
                                                <td>
                                                    <small>{{ $auditoria->created_at->format('d/m/Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $auditoria->usuario->name }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ str_replace('_', ' ', $auditoria->accion) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($auditoria->motivo, 80) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">No hay cambios registrados para esta nota.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
