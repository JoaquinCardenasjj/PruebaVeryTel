@extends('layouts.app')

@section('title', 'Inicio')



@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gestión de Inventario de Cámaras</h2>
        <button class="btn btn-primary" onclick="showCreateForm()">
            <i class="bi bi-plus-lg"></i> Nueva Cámara
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="search-input" class="form-control" placeholder="Buscar por modelo, ubicación o ID...">
                </div>
                <div class="col-md-3">
                    <select id="filter-estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="Activa">Activa</option>
                        <option value="Inactiva">Inactiva</option>
                        <option value="En Mantenimiento">En Mantenimiento</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="filter-localidad" class="form-control" placeholder="Localidad...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="loadCameras()">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="grid-selector" class="row">
    </div>

    <div id="form-container" class="d-none">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h4 class="mb-0" id="form-title">Nueva Cámara</h4>
                <button class="btn-close btn-close-white" onclick="hideForm()"></button>
            </div>
            <div class="card-body">
                <form id="camera-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ID Cámara (Único)</label>
                            <input type="text" id="id_camera" class="form-control" placeholder="Ej: CAM-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Modelo</label>
                            <input type="text" id="modelo" class="form-control" placeholder="Ej: Hikvision..." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Localidad</label>
                            <input type="text" id="localidad" class="form-control" placeholder="Ej: Teusaquillo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección / Ubicación</label>
                            <input type="text" id="ubicacion" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Latitud (4.4 a 4.9)</label>
                            <input type="number" step="any" id="latitud" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Longitud (-74.3 a -73.9)</label>
                            <input type="number" step="any" id="longitud" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado</label>
                            <select id="estado" class="form-select" required>
                                <option value="Activa">Activa</option>
                                <option value="Inactiva">Inactiva</option>
                                <option value="En Mantenimiento">En Mantenimiento</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Último Mantenimiento</label>
                            <input type="date" id="ultimo_mantenimiento" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="hideForm()">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btn-save">Guardar Cámara</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <nav class="mt-4">
        <ul class="pagination justify-content-center" id="pagination-container"></ul>
    </nav>
</div>

</script>
<script src="{{ asset('camera.js') }}"></script>

@endsection