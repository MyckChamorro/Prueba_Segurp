@extends('layouts.main')

@section('title', 'Gestión de Notas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-clipboard-check"></i> Gestión de Notas
            </h1>
            <a href="{{ route('docente.notas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar Nueva Nota
            </a>
        </div>

        @if($asignaturas->count() > 0)
            @foreach($asignaturas as $asignatura)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-book"></i> {{ $asignatura->nombre }} 
                            <small class="text-light">({{ $asignatura->codigo }})</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($asignatura->notas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th class="text-center">Nota 1</th>
                                            <th class="text-center">Nota 2</th>
                                            <th class="text-center">Nota 3</th>
                                            <th class="text-center">Promedio</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asignatura->notas as $nota)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-circle me-2"></i>
                                                        <div>
                                                            <strong>{{ $nota->estudiante->name }}</strong><br>
                                                            <small class="text-muted">{{ $nota->estudiante->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $nota->nota_1 ? ($nota->nota_1 >= 14.5 ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}">
                                                        {{ $nota->nota_1 ?? 'Pendiente' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $nota->nota_2 ? ($nota->nota_2 >= 14.5 ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}">
                                                        {{ $nota->nota_2 ?? 'Pendiente' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $nota->nota_3 ? ($nota->nota_3 >= 14.5 ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}">
                                                        {{ $nota->nota_3 ?? 'Pendiente' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($nota->promedio)
                                                        <strong class="fs-5 {{ $nota->promedio >= 14.5 ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($nota->promedio, 2) }}
                                                        </strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $nota->color_estado }}">
                                                        {{ ucfirst($nota->estado_final) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('docente.notas.show', $nota) }}" 
                                                           class="btn btn-sm btn-outline-info" title="Ver">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('docente.notas.edit', $nota) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $nota->id }}" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Modal de Confirmación de Eliminación -->
                                                    <div class="modal fade" id="deleteModal{{ $nota->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ route('docente.notas.destroy', $nota) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Confirmar Eliminación</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="alert alert-warning">
                                                                            <i class="bi bi-exclamation-triangle"></i>
                                                                            <strong>¿Estás seguro de eliminar esta nota?</strong>
                                                                            <br>Estudiante: {{ $nota->estudiante->name }}
                                                                            <br>Asignatura: {{ $nota->asignatura->nombre }}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="motivo{{ $nota->id }}" class="form-label">
                                                                                <strong>Motivo de eliminación *</strong>
                                                                            </label>
                                                                            <textarea class="form-control" name="motivo" 
                                                                                    id="motivo{{ $nota->id }}" rows="3" 
                                                                                    placeholder="Explique el motivo de la eliminación..."
                                                                                    required minlength="10" maxlength="500"></textarea>
                                                                            <div class="form-text">Mínimo 10 caracteres</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                            Cancelar
                                                                        </button>
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="bi bi-trash"></i> Eliminar Nota
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Estadísticas de la asignatura -->
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $asignatura->notas->count() }}</h5>
                                            <small>Total Estudiantes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $asignatura->notas->where('estado_final', 'aprobado')->count() }}</h5>
                                            <small>Aprobados</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $asignatura->notas->where('estado_final', 'reprobado')->count() }}</h5>
                                            <small>Reprobados</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ number_format($asignatura->notas->where('promedio', '!=', null)->avg('promedio'), 2) }}</h5>
                                            <small>Promedio Asignatura</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No hay notas registradas para esta asignatura.</p>
                                <a href="{{ route('docente.notas.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Registrar Primera Nota
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-book-half fs-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No tienes asignaturas asignadas</h4>
                    <p class="text-muted">Contacta al administrador para que te asigne asignaturas.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
