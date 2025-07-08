@extends('layouts.main')

@section('title', 'Detalle de Asignatura')

@section('content')
<div class="container">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $asignatura->codigo }} - {{ $asignatura->nombre }}</h1>
            <p class="text-muted mb-0">Gestión completa de la asignatura</p>
        </div>
        <div>
            <a href="{{ route('docente.asignaturas.edit', $asignatura) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('docente.asignaturas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información de la asignatura -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Código:</dt>
                        <dd class="col-sm-8"><strong>{{ $asignatura->codigo }}</strong></dd>
                        
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8">{{ $asignatura->nombre }}</dd>
                        
                        <dt class="col-sm-4">Créditos:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-info">{{ $asignatura->creditos }}</span>
                        </dd>
                        
                        <dt class="col-sm-4">Creada:</dt>
                        <dd class="col-sm-8">{{ $asignatura->created_at->format('d/m/Y') }}</dd>
                    </dl>
                    
                    @if($asignatura->descripcion)
                        <h6>Descripción:</h6>
                        <p class="text-muted">{{ $asignatura->descripcion }}</p>
                    @endif
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
                            <h3 class="text-primary">{{ $asignatura->estudiantes->count() }}</h3>
                            <small class="text-muted">Estudiantes</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success">{{ $asignatura->notas->count() }}</h3>
                            <small class="text-muted">Notas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Docentes asignados -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Docentes Asignados</h5>
                </div>
                <div class="card-body">
                    @if($asignatura->docentes->count() > 0)
                        <div class="row">
                            @foreach($asignatura->docentes as $docente)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($docente->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $docente->name }}</strong><br>
                                            <small class="text-muted">{{ $docente->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay docentes asignados a esta asignatura.</p>
                    @endif
                </div>
            </div>

            <!-- Estudiantes inscritos -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Estudiantes Inscritos ({{ $asignatura->estudiantes->count() }})</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-user-plus"></i> Agregar Estudiante
                    </button>
                </div>
                <div class="card-body">
                    @if($asignatura->estudiantes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Promedio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asignatura->estudiantes as $estudiante)
                                        @php
                                            $notasEstudiante = $asignatura->notas->where('estudiante_id', $estudiante->id);
                                            $promedio = $notasEstudiante->avg('nota');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-secondary text-white rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
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
                                            </td>
                                            <td>
                                                @if($promedio)
                                                    <span class="badge bg-{{ $promedio >= 3.0 ? 'success' : 'warning' }}">
                                                        {{ number_format($promedio, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin notas</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('docente.notas.index', ['estudiante_id' => $estudiante->id, 'asignatura_id' => $asignatura->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver notas">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay estudiantes inscritos</h6>
                            <p class="text-muted">Agregue estudiantes a esta asignatura</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar estudiante -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('docente.asignaturas.asignar-estudiante', $asignatura) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="estudiante_id" class="form-label">Seleccionar Estudiante</label>
                        <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                            <option value="">Seleccione un estudiante...</option>
                            <!-- Los estudiantes se cargarán via JavaScript -->
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <small>Solo se muestran estudiantes activos que no están ya inscritos en esta asignatura.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Estudiante</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Cargar estudiantes disponibles cuando se abre el modal
    document.getElementById('addStudentModal').addEventListener('show.bs.modal', function () {
        fetch(`{{ route('docente.asignaturas.estudiantes-json', $asignatura) }}`)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('estudiante_id');
                select.innerHTML = '<option value="">Seleccione un estudiante...</option>';
                
                // Filtrar estudiantes ya inscritos
                const estudiantesInscritos = @json($asignatura->estudiantes->pluck('id'));
                const estudiantesDisponibles = data.filter(estudiante => 
                    !estudiantesInscritos.includes(estudiante.id)
                );
                
                estudiantesDisponibles.forEach(estudiante => {
                    const option = document.createElement('option');
                    option.value = estudiante.id;
                    option.textContent = `${estudiante.name} (${estudiante.email})`;
                    select.appendChild(option);
                });
                
                if (estudiantesDisponibles.length === 0) {
                    select.innerHTML = '<option value="">No hay estudiantes disponibles</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('estudiante_id').innerHTML = '<option value="">Error al cargar estudiantes</option>';
            });
    });
</script>
@endsection
