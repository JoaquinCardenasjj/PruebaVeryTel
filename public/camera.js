// URL de la API que devuelve las cámaras. Cámbiala si trabajas en local o con otro dominio.
const API_URL = '/api-proxy/cameras';

document.addEventListener('DOMContentLoaded', () => {
    loadCameras();

    // Escucha el texto de búsqueda mientras escribes y recarga los resultados con un pequeño retraso.
    document.getElementById('search-input').addEventListener('input', debounce(() => {
        loadCameras();
    }, 500));
});

async function loadCameras(url = API_URL) {
    const search = document.getElementById('search-input').value;
    const estado = document.getElementById('filter-estado').value;
    const localidad = document.getElementById('filter-localidad').value;

    // Arma los parámetros de búsqueda para enviarlos al backend de Django.
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (estado) params.append('estado', estado);
    if (localidad) params.append('localidad', localidad);

    try {
        console.log("Enviando parámetros:", params.toString());
        const response = await fetch(`${url}?${params.toString()}`);
        
        const data = await response.json();
        renderCameras(data.results || data); // Ajustar si usas paginación de DRF
    } catch (error) {
        console.error("Error cargando cámaras:", error);
    }
}

function renderCameras(cameras) {
    const container = document.getElementById('grid-selector');
    container.innerHTML = '';

    cameras.forEach(camera => {
        // Decide el color de la etiqueta según el estado de la cámara.
        const badgeColor = camera.estado === 'Activa' ? 'success' : 
                          (camera.estado === 'Inactiva' ? 'danger' : 'warning');

        container.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card card-tool h-100 shadow-sm border-start border-${badgeColor} border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title fw-bold">${camera.id_camera}</h5>
                            <span class="badge bg-${badgeColor}">${camera.estado}</span>
                        </div>
                        <p class="card-text mb-1"><strong>Modelo:</strong> ${camera.modelo}</p>
                        <p class="card-text mb-1 text-muted"><i class="bi bi-geo-alt"></i> ${camera.ubicacion}</p>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetail('${camera.id_camera}')">Detalle</button>
                            <div>
                                <button class="btn btn-sm btn-light" onclick="editCamera('${camera.id_camera}')"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-light text-danger" onclick="deleteCamera('${camera.id_camera}')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
}

// Pequeña utilidad para esperar antes de ejecutar la búsqueda y no llamar a la API en cada letra.
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

let isEditing = false;

function showCreateForm() {
    isEditing = false;
    document.getElementById('camera-form').reset();
    document.getElementById('id_camera').readOnly = false;
    document.getElementById('form-title').innerText = "Nueva Cámara";
    
    document.getElementById('grid-selector').classList.add('d-none');
    document.getElementById('form-container').classList.remove('d-none');
}

function hideForm() {
    document.getElementById('form-container').classList.add('d-none');
    document.getElementById('grid-selector').classList.remove('d-none');
}

// Función para guardar (POST o PUT)
document.getElementById('camera-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.getElementById('id_camera').value;
    const data = {
        id_camera: id,
        modelo: document.getElementById('modelo').value,
        ubicacion: document.getElementById('ubicacion').value,
        localidad: document.getElementById('localidad').value,
        latitud: parseFloat(document.getElementById('latitud').value),
        longitud: parseFloat(document.getElementById('longitud').value),
        estado: document.getElementById('estado').value,
        ultimo_mantenimiento: document.getElementById('ultimo_mantenimiento').value || null
    };
    const token = document.querySelector('input[name="_token"]').value;
    const method = isEditing ? 'PUT' : 'POST';
    const url = isEditing ? `/api-proxy/cameras/${id}` : `/api-proxy/cameras`;

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token 
             },
            
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert("¡Cámara guardada con éxito!");
            hideForm();
            loadCameras(); // Recargamos el listado
        } else {
            // Aquí mostramos los errores de validación que vienen de Django (Bogotá, ID único, etc)
            alert("Error: " + JSON.stringify(result));
        }
    } catch (error) {
        console.error("Error al guardar:", error);
    }
});

async function editCamera(id) {
    isEditing = true;
    try {
        // Primero obtenemos los datos de esa cámara
        const response = await fetch(`/api-proxy/cameras/${id}`);
        const camera = await response.json();

        // Llenamos el formulario
        document.getElementById('id_camera').value = camera.id_camera;
        document.getElementById('id_camera').readOnly = true; // REQUISITO 4.1.3
        document.getElementById('modelo').value = camera.modelo;
        document.getElementById('ubicacion').value = camera.ubicacion;
        document.getElementById('localidad').value = camera.localidad;
        document.getElementById('latitud').value = camera.latitud;
        document.getElementById('longitud').value = camera.longitud;
        document.getElementById('estado').value = camera.estado;
        document.getElementById('ultimo_mantenimiento').value = camera.ultimo_mantenimiento;

        document.getElementById('form-title').innerText = "Editar Cámara: " + id;
        document.getElementById('grid-selector').classList.add('d-none');
        document.getElementById('form-container').classList.remove('d-none');
    } catch (error) {
        alert("No se pudo cargar la cámara para editar.");
    }
}

async function deleteCamera(id) {
    if (!confirm(`¿Estás seguro de que deseas eliminar la cámara ${id}?`)) {
        return;
    }

    // Obtenemos el token CSRF (igual que hicimos en el Guardar)
    const token = document.querySelector('input[name="_token"]')?.value || 
                  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/api-proxy/cameras/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            alert("Cámara eliminada correctamente.");
            loadCameras(); // Recarga la lista para que la cámara ya no aparezca
        } else {
            const error = await response.json();
            alert("Error al eliminar: " + (error.detail || "Consulte al administrador"));
        }
    } catch (error) {
        console.error("Error en la petición de borrado:", error);
        alert("Ocurrió un error al intentar eliminar la cámara.");
    }
}