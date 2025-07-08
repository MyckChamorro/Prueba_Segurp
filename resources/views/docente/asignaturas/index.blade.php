@extends('layouts.main')

@section('title', 'Gestión de Asignaturas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Asignaturas</h1>
        <a href="{{ route('docente.asignaturas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Asignatura
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($asignaturas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Créditos</th>
                                <th>Docentes</th>
                                <th>Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaturas as $asignatura)
                                <tr>
                                    <td>
                                        <strong>{{ $asignatura->codigo }}</strong>
                                    </td>
                                    <td>{{ $asignatura->nombre }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $asignatura->creditos }}</span>
                                    </td>
                                    <td>
                                        @if($asignatura->docentes->count() > 0)
                                            @foreach($asignatura->docentes as $docente)
                                                <span class="badge bg-secondary">{{ $docente->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Sin docentes asignados</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $asignatura->estudiantes->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('docente.asignaturas.show', $asignatura) }}" 
                                               class="btn btn-sm btn-outline-info" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('docente.asignaturas.edit', $asignatura) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $asignatura->id }}" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de confirmación para eliminar -->
                                <div class="modal fade" id="deleteModal{{ $asignatura->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de que desea eliminar la asignatura 
                                                <strong>{{ $asignatura->codigo }} - {{ $asignatura->nombre }}</strong>?
                                                <br><br>
                                                <small class="text-muted">
                                                    Esta acción no se puede deshacer. Solo se puede eliminar si no tiene notas registradas.
                                                </small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('docente.asignaturas.destroy', $asignatura) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay asignaturas registradas</h5>
                    <p class="text-muted">Comience creando una nueva asignatura</p>
                    <a href="{{ route('docente.asignaturas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Asignatura
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
