@extends('layouts.main')

@section('title', 'Historial de Auditorías')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-file-text"></i> Historial de Auditorías
            </h1>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('docente.auditorias.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="accion" class="form-label">Acción</label>
                            <select class="form-select" name="accion" id="accion">
                                <option value="">Todas las acciones</option>
                                @foreach($acciones as $accion)
                                    <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $accion)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_desde" class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" 
                                   value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" 
                                   value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                                <a href="{{ route('docente.auditorias.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4>{{ $auditorias->total() }}</h4>
                        <small>Total Registros</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ \App\Models\Auditoria::where('accion', 'like', '%crear%')->count() }}</h4>
                        <small>Creaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4>{{ \App\Models\Auditoria::where('accion', 'like', '%editar%')->count() }}</h4>
                        <small>Modificaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h4>{{ \App\Models\Auditoria::where('accion', 'like', '%eliminar%')->count() }}</h4>
                        <small>Eliminaciones</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Auditorías -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-list"></i> Registros de Auditoría
                </h5>
            </div>
            <div class="card-body">
                @if($auditorias->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Motivo</th>
                                    <th class="text-center">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditorias as $auditoria)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $auditoria->created_at->format('d/m/Y') }}</strong><br>
                                                <small class="text-muted">{{ $auditoria->created_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-2"></i>
                                                <div>
                                                    <strong>{{ $auditoria->usuario->name }}</strong><br>
                                                    <small class="text-muted">{{ $auditoria->usuario->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match(true) {
                                                    str_contains($auditoria->accion, 'crear') => 'bg-success',
                                                    str_contains($auditoria->accion, 'editar') || str_contains($auditoria->accion, 'actualizar') => 'bg-warning',
                                                    str_contains($auditoria->accion, 'eliminar') => 'bg-danger',
                                                    str_contains($auditoria->accion, 'acceso') => 'bg-info',
                                                    default => 'bg-secondary'
                                                };
                                                $icon = match(true) {
                                                    str_contains($auditoria->accion, 'crear') => 'bi-plus-circle',
                                                    str_contains($auditoria->accion, 'editar') || str_contains($auditoria->accion, 'actualizar') => 'bi-pencil',
                                                    str_contains($auditoria->accion, 'eliminar') => 'bi-trash',
                                                    str_contains($auditoria->accion, 'acceso') => 'bi-door-open',
                                                    default => 'bi-gear'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                <i class="{{ $icon }}"></i> {{ ucfirst(str_replace('_', ' ', $auditoria->accion)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 300px;">
                                                {{ Str::limit($auditoria->motivo, 100) }}
                                                @if(strlen($auditoria->motivo) > 100)
                                                    <button class="btn btn-link btn-sm p-0" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#motivoModal{{ $auditoria->id }}">
                                                        <small>Ver más...</small>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detalleModal{{ $auditoria->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal de Detalle -->
                                    <div class="modal fade" id="detalleModal{{ $auditoria->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detalle de Auditoría</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>ID:</strong> {{ $auditoria->id }}<br>
                                                            <strong>Usuario:</strong> {{ $auditoria->usuario->name }}<br>
                                                            <strong>Email:</strong> {{ $auditoria->usuario->email }}<br>
                                                            <strong>Acción:</strong> {{ $auditoria->accion }}<br>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Fecha:</strong> {{ $auditoria->created_at->format('d/m/Y H:i:s') }}<br>
                                                            <strong>Hace:</strong> {{ $auditoria->created_at->diffForHumans() }}<br>
                                                            <strong>IP:</strong> <span class="text-muted">No registrada</span><br>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <strong>Motivo completo:</strong>
                                                            <div class="border p-3 mt-2 bg-light">
                                                                {{ $auditoria->motivo }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Motivo (si es necesario) -->
                                    @if(strlen($auditoria->motivo) > 100)
                                        <div class="modal fade" id="motivoModal{{ $auditoria->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Motivo Completo</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="border p-3 bg-light">
                                                            {{ $auditoria->motivo }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Cerrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $auditorias->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-file-text-x fs-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No se encontraron registros</h4>
                        <p class="text-muted">No hay auditorías que coincidan con los filtros seleccionados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.text-wrap {
    word-wrap: break-word;
    word-break: break-word;
}
</style>
@endpush
