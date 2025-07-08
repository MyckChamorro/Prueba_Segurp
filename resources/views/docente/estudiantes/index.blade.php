@extends('layouts.main')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Estudiantes</h1>
        <a href="{{ route('docente.estudiantes.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Estudiante
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
            @if($estudiantes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Asignaturas</th>
                                <th>Notas</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiantes as $estudiante)
                                <tr>
                                    <td>
                                        <strong>{{ $estudiante->codigo_estudiante ?: 'Sin código' }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle me-2" 
                                                 style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($estudiante->name, 0, 1)) }}
                                            </div>
                                            {{ $estudiante->name }}
                                        </div>
                                    </td>
                                    <td>{{ $estudiante->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $estudiante->estado === 'activo' ? 'success' : 'danger' }}">
                                            {{ ucfirst($estudiante->estado) }}
                                        </span>
                                        @if($estudiante->estado === 'inactivo' && $estudiante->motivo_inactivo)
                                            <br><small class="text-muted">{{ $estudiante->motivo_inactivo }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $estudiante->asignaturas->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $estudiante->notas->count() }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $estudiante->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('docente.estudiantes.show', $estudiante) }}" 
                                               class="btn btn-sm btn-outline-info" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('docente.estudiantes.edit', $estudiante) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $estudiante->id }}" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de confirmación para eliminar -->
                                <div class="modal fade" id="deleteModal{{ $estudiante->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de que desea eliminar al estudiante 
                                                <strong>{{ $estudiante->name }}</strong>?
                                                <br><br>
                                                <small class="text-muted">
                                                    Esta acción no se puede deshacer. Solo se puede eliminar si no tiene notas registradas.
                                                </small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('docente.estudiantes.destroy', $estudiante) }}" 
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
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay estudiantes registrados</h5>
                    <p class="text-muted">Comience registrando el primer estudiante</p>
                    <a href="{{ route('docente.estudiantes.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Registrar Primer Estudiante
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($estudiantes->count() > 0)
        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h3>{{ $estudiantes->count() }}</h3>
                        <small>Total Estudiantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h3>{{ $estudiantes->where('estado', 'activo')->count() }}</h3>
                        <small>Activos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h3>{{ $estudiantes->where('estado', 'inactivo')->count() }}</h3>
                        <small>Inactivos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body text-center">
                        <h3>{{ $estudiantes->sum(function($estudiante) { return $estudiante->asignaturas->count(); }) }}</h3>
                        <small>Total Inscripciones</small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
