@extends('layouts.app')

@section('title', 'Inicio')



@section('content')

<div class="container-fluid" id="tools-container">

    <div id="grid-selector">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="bi bi-ticket-perforated"></i> Gestión de Tickets</h2>
                <button class="btn btn-primary" onclick="showTicketCreateForm()">
                    <i class="bi bi-plus-lg"></i> Nuevo Ticket
                </button>
            </div>
            <div class="card shadow-sm mb-4">

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select id="ticket-filter-estado" class="form-select" onchange="loadTickets()">
                                <option value="">Todos los Estados</option>
                                <option value="Nuevo">Nuevo</option>
                                <option value="En curso">En curso</option>
                                <option value="Resuelto">Resuelto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="ticket-filter-prioridad" class="form-select" onchange="loadTickets()">
                                <option value="">Todas las Prioridades</option>
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-outline-secondary" onclick="loadTickets()">
                                <i class="bi bi-arrow-clockwise"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Ticket</th>
                                <th>Cámara</th>
                                <th>Tipo de Falla</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Fecha Apertura</th>
                                <th>Fecha Cierre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tickets-table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div id="form-container" class="d-none">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h4 class="mb-0" id="ticket-form-title">Nuevo Ticket de Mantenimiento</h4>
                <button class="btn-close btn-close-white" onclick="hideTicketForm()"></button>
            </div>
            <div class="card-body">
                <form id="ticket-main-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Código del Ticket (ID)</label>
                            <input type="text" id="id_ticket_input" class="form-control" placeholder="Ej: TK-2024-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cámara Afectada</label>
                            <select id="ticket_camera_select" class="form-select" required>
                                <option value="">Seleccione una cámara...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Falla</label>
                            <select id="ticket_tipo_input" class="form-select" required>
                                <option value="Correctivo">Correctivo</option>
                                <option value="Preventivo">Preventivo</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prioridad</label>
                            <select id="ticket_prioridad_input" class="form-select" required>
                                <option value="Baja">Baja</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado Inicial</label>
                            <input type="text" id="ticket_estado_display" class="form-control" value="Nuevo" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción Detallada</label>
                            <textarea id="ticket_descripcion_input" class="form-control" rows="4" placeholder="Describa el problema observado..." required></textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border" onclick="hideTicketForm()">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-tool {
        cursor: pointer;
        transition: 0.3s;
    }

    .card-tool:hover {
        transform: scale(1.03);
        border-color: #0d6efd;
    }
</style>
<script>

</script>
<script src="{{ asset('tickets.js') }}"></script>

@endsection