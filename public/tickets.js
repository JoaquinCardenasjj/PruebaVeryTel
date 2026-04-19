// URL del Proxy para Tickets
const TICKETS_API_URL = '/api-proxy/tickets';

async function loadTickets() {
    const estado = document.getElementById('ticket-filter-estado').value;
    const prioridad = document.getElementById('ticket-filter-prioridad').value;

    const params = new URLSearchParams();
    if (estado) params.append('estado', estado);
    if (prioridad) params.append('prioridad', prioridad);

    try {
        const response = await fetch(`${TICKETS_API_URL}?${params.toString()}`);
        const tickets = await response.json();
        renderTicketsTable(tickets.results || tickets);
    } catch (error) {
        console.error("Error cargando tickets:", error);
    }
}

function renderTicketsTable(tickets) {
    const tbody = document.getElementById('tickets-table-body');
    tbody.innerHTML = '';

    tickets.forEach(ticket => {
        // Colores para Prioridad
        const priorityClass = ticket.prioridad === 'Alta' ? 'bg-danger' : (ticket.prioridad === 'Media' ? 'bg-warning text-dark' : 'bg-info text-dark');
        
        // Colores para Estado
        const statusClass = ticket.estado === 'Resuelto' ? 'bg-success' : (ticket.estado === 'En curso' ? 'bg-primary' : 'bg-secondary');

        tbody.innerHTML += `
            <tr>
                <td><span class="fw-bold">#${ticket.id_ticket}</span></td>
                <td><span class="badge btn-light border text-dark">${ticket.camera}</span></td>
                <td>${ticket.tipo}</td>
                <td><span class="badge ${priorityClass}">${ticket.prioridad}</span></td>
                <td><span class="badge ${statusClass}">${ticket.estado}</span></td>
                <td><small>${new Date(ticket.fecha_apertura).toLocaleString()}</small></td>
                <td><small>${ticket.fecha_cierre ? new Date(ticket.fecha_cierre).toLocaleString() : '---'}</small></td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Cambiar Estado
                        </button>
                        <ul class="dropdown-menu">
                            ${ticket.estado === 'Nuevo' ? `<li><a class="dropdown-item" href="#" onclick="cambiarEstadoTicket('${ticket.id_ticket}', 'En curso')">Empezar (En curso)</a></li>` : ''}
                            ${ticket.estado !== 'Resuelto' ? `<li><a class="dropdown-item" href="#" onclick="cambiarEstadoTicket('${ticket.id_ticket}', 'Resuelto')">Resolver</a></li>` : '<li><span class="dropdown-item disabled">Finalizado</span></li>'}
                        </ul>
                    </div>
                </td>
                <td>
                 <div class="d-flex justify-content-between">                            
                            <div>
                                <button class="btn btn-sm btn-light" onclick="editTicket('${ticket.id_ticket}')"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-light text-danger" onclick="deleteTicket('${ticket.id_ticket}')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                </td>
            </tr>
        `;
    });
}

async function cambiarEstadoTicket(idTicket, nuevoEstado) {
    // 1. Confirmación visual para el usuario
    if (!confirm(`¿Deseas cambiar el estado del ticket #${idTicket} a "${nuevoEstado}"?`)) {
        return;
    }

    // 2. Obtener el Token CSRF de Laravel
    const token = document.querySelector('input[name="_token"]')?.value || 
                  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const responseDetail = await fetch(`/api-proxy/tickets/${idTicket}`);
        const ticket = await responseDetail.json();
        ticket.estado = nuevoEstado; // Solo actualizamos el campo de estado para la petición

        // 3. Petición al Proxy de Laravel
        const response = await fetch(`/api-proxy/tickets/${idTicket}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            // Enviamos solo el campo 'estado' para que el backend lo procese
            body: JSON.stringify(ticket)
        });

        const result = await response.json();

        if (response.ok) {
            alert("¡Estado actualizado con éxito!");
            
            // Recargamos la tabla de tickets para ver los cambios
            if (typeof loadTickets === 'function') {
                loadTickets();
            } else {
                location.reload(); // Fallback si no hay función de carga parcial
            }
        } else {
            /* Si Django devuelve un error (ej: "No se puede volver a un estado anterior"),
               lo capturamos y lo mostramos de forma amigable.
            */
            let mensajeError = "No se pudo cambiar el estado.";
            
            if (Array.isArray(result)) {
                mensajeError = result[0];
            } else if (result.non_field_errors) {
                mensajeError = result.non_field_errors[0];
            } else if (result.detail) {
                mensajeError = result.detail;
            }

            alert("Validación del Sistema: " + mensajeError);
        }
    } catch (error) {
        console.error("Error en la conexión:", error);
        alert("Ocurrió un error de red al intentar actualizar el ticket.");
    }
}

let isEditingTicket = false;
let currentTicketId = null;

// Muestra el formulario y carga las cámaras disponibles
async function showTicketCreateForm() {
    isEditingTicket = false;
    document.getElementById('ticket-main-form').reset();
    document.getElementById('ticket-form-title').innerText = "Nuevo Ticket de Mantenimiento";

    document.getElementById('id_ticket_input').readOnly = false;
    document.getElementById('id_ticket_input').classList.remove('bg-light');

    // Cargar cámaras para el select si no están cargadas
    await loadCamerasToSelect();
    document.getElementById('grid-selector').classList.add('d-none');    
    document.getElementById('form-container').classList.remove('d-none');
}

function hideTicketForm() {    
    document.getElementById('form-container').classList.add('d-none');
    document.getElementById('grid-selector').classList.remove('d-none');
}

// Carga las cámaras desde el API para el dropdown
async function loadCamerasToSelect() {
    const select = document.getElementById('ticket_camera_select');
    try {
        const response = await fetch('/api-proxy/cameras');
        const data = await response.json();
        const cameras = data.results || data;

        select.innerHTML = '<option value="">Seleccione una cámara...</option>';
        cameras.forEach(cam => {
            select.innerHTML += `<option value="${cam.id_camera}">${cam.id_camera} - ${cam.modelo}</option>`;
        });
    } catch (error) {
        console.error("Error cargando cámaras para el ticket:", error);
    }
}
function hideForm() {
    document.getElementById('form-container').classList.add('d-none');
    document.getElementById('grid-selector').classList.remove('d-none');
}
// Guardar Ticket (POST / PUT)
document.getElementById('ticket-main-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const token = document.querySelector('input[name="_token"]').value;
    const data = {
        id_ticket: document.getElementById('id_ticket_input').value,
        camera: document.getElementById('ticket_camera_select').value,
        tipo: document.getElementById('ticket_tipo_input').value,
        prioridad: document.getElementById('ticket_prioridad_input').value,
        descripcion: document.getElementById('ticket_descripcion_input').value,
        estado: isEditingTicket ? undefined : 'Nuevo' // Django maneja el estado en PUT
    };

    const method = isEditingTicket ? 'PUT' : 'POST';
    const url = isEditingTicket ? `/api-proxy/tickets/${currentTicketId}` : `/api-proxy/tickets`;

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token 
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            alert(isEditingTicket ? "Ticket actualizado" : "Ticket creado con éxito");
            hideTicketForm();
            loadTickets(); // Refresca la tabla
        } else {
            const err = await response.json();
            alert("Error: " + JSON.stringify(err));
        }
    } catch (error) {
        console.error("Error al procesar ticket:", error);
    }
});

// Función para cargar datos en modo edición
async function editTicket(id) {
    isEditingTicket = true;
    currentTicketId = id;
    
    await loadCamerasToSelect();

    try {
        

        const response = await fetch(`/api-proxy/tickets/${id}`);
        const ticket = await response.json();

        const idInput = document.getElementById('id_ticket_input');
        idInput.value = ticket.id_ticket;
        idInput.readOnly = true; 
        idInput.classList.add('bg-light'); // Toque visual de "bloqueado"

        document.getElementById('ticket_camera_select').value = ticket.camera;
        document.getElementById('ticket_tipo_input').value = ticket.tipo;
        document.getElementById('ticket_prioridad_input').value = ticket.prioridad;
        document.getElementById('ticket_descripcion_input').value = ticket.descripcion;
        document.getElementById('ticket_estado_display').value = ticket.estado;

        document.getElementById('ticket-form-title').innerText = "Editando Ticket #" + id;
        document.getElementById('grid-selector').classList.add('d-none');
        document.getElementById('form-container').classList.remove('d-none');
    } catch (error) {
        alert("Error al cargar el ticket");
    }
}

async function deleteTicket(id) {
    if (!confirm(`¿Estás seguro de que deseas eliminar el ticket ${id}?`)) {
        return;
    }

    // Obtenemos el token CSRF (igual que hicimos en el Guardar)
    const token = document.querySelector('input[name="_token"]')?.value || 
                  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/api-proxy/tickets/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            alert("Ticket eliminado correctamente.");
            loadTickets(); // Recarga la lista para que el ticket ya no aparezca
        } else {
            const error = await response.json();
            alert("Error al eliminar: " + (error.detail || "Consulte al administrador"));
        }
    } catch (error) {
        console.error("Error en la petición de borrado:", error);
        alert("Ocurrió un error al intentar eliminar el ticket.");
    }
}

// Inicializar carga al entrar
document.addEventListener('DOMContentLoaded', loadTickets);

