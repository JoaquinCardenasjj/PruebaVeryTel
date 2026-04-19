@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Cámaras Activas</h6>
                    <h3 id="kpi-activas">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6>Tickets Abiertos</h6>
                    <h3 id="kpi-tickets-abiertos">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Resolución Promedio</h6>
                    <h3 id="kpi-promedio">0d</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Críticos/Altos</h6>
                    <h3 id="kpi-criticos">0%</h3>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
            z-index: 1;
        }

        .info-legend {
            background: white;
            padding: 10px;
            line-height: 18px;
            color: #555;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .info-legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
            border-radius: 50%;
        }
    </style>


    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Distribución de Cámaras por Estado</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEstadoCamaras"></canvas>
                    </div>
                    <div class="card-footer text-center">
                        <small id="total-camaras-text" class="text-muted fw-bold"></small>
                    </div>
                </div>
            </div>
            <div class="card shadow mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Geolocalización de Cámaras en Bogotá</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
            <div class="col-md-8 mt-4">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Tickets por Localidad (Correctivo vs Preventivo)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="barChartLocalidad"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Inicializamos el mapa de Leaflet y procesamos los datos de cámaras y tickets
    async function initMap() {
        // Configuramos el mapa centrado en Bogotá
        const map = L.map('map').setView([4.6097, -74.0817], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        try {
            // Solicitamos en paralelo los datos de cámaras y tickets desde nuestras APIs
            const [resCamaras, resTickets] = await Promise.all([
                fetch('/api-proxy/cameras'),
                fetch('/api-proxy/tickets')
            ]);

            const camaras = await resCamaras.json();
            const tickets = await resTickets.json();
            await renderDashboard(camaras);
            await updateKPICards(camaras, tickets);
            await renderBarChart(camaras, tickets);
            // Preparamos un mapa con los tickets abiertos de cada cámara
            const ticketsAbiertosPorCamara = {};
            (tickets.results || tickets).forEach(t => {
                if (t.estado !== 'Resuelto') {
                    ticketsAbiertosPorCamara[t.camera] = (ticketsAbiertosPorCamara[t.camera] || 0) + 1;
                }
            });

            // Definimos el color del marcador según el estado de la cámara
            const getColor = (estado) => {
                switch (estado) {
                    case 'Activa':
                        return '#28a745'; // Verde
                    case 'En Mantenimiento':
                        return '#ffc107'; // Amarillo
                    case 'Inactiva':
                        return '#dc3545'; // Rojo
                    default:
                        return '#6c757d';
                }
            };

            // Añadimos marcadores en el mapa para cada cámara
            (camaras.results || camaras).forEach(cam => {
                const lat = parseFloat(cam.latitud);
                const lng = parseFloat(cam.longitud);

                if (!isNaN(lat) && !isNaN(lng)) {
                    const color = getColor(cam.estado);
                    const circulo = L.circleMarker([lat, lng], {
                        radius: 8,
                        fillColor: color,
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map);

                    // Mostramos un popup con los detalles de la cámara y sus tickets abiertos
                    const numTickets = ticketsAbiertosPorCamara[cam.id_camera] || 0;

                    circulo.bindPopup(`
                    <div style="font-family: Arial">
                        <h6 class="fw-bold">Cámara: ${cam.id_camera}</h6>
                        <hr class="my-1">
                        <b>Modelo:</b> ${cam.modelo}<br>
                        <b>Ubicación:</b> ${cam.ubicacion}<br>
                        <b>Estado:</b> <span style="color: ${color}">${cam.estado}</span><br>
                        <b>Mantenimiento:</b> ${cam.fecha_ultimo_mantenimiento || 'N/A'}<br>
                        <b>Tickets Abiertos:</b> <span class="badge bg-info text-dark">${numTickets}</span>
                    </div>
                `);
                }
            });

            // Agregamos la leyenda al mapa para que el usuario entienda los colores
            const legend = L.control({
                position: 'bottomright'
            });
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'info-legend');
                const states = ['Activa', 'En Mantenimiento', 'Inactiva'];
                const colors = ['#28a745', '#ffc107', '#dc3545'];

                div.innerHTML += '<strong>Leyenda de Estados</strong><br>';
                for (let i = 0; i < states.length; i++) {
                    div.innerHTML += `<i style="background: ${colors[i]}"></i> ${states[i]}<br>`;
                }
                return div;
            };
            legend.addTo(map);

        } catch (error) {
            console.error("Error cargando el mapa:", error);
        }
    }
    async function renderBarChart(camaras, tickets) {
        const localidades = [...new Set(camaras.map(c => c.localidad))]; // Extraemos localidades únicas
        const dataCorrectivo = localidades.map(loc => {
            return tickets.filter(t => {
                const tipoTicket = String(t.tipo).toLowerCase();
                const camaraAsociada = camaras.find(c => c.id_camera === t.camera);

                return tipoTicket === 'correctivo' && camaraAsociada?.localidad === loc;
            }).length;
        });

        const dataPreventivo = localidades.map(loc => {
            return tickets.filter(t => {
                const tipoTicket = String(t.tipo).toLowerCase();
                const camaraAsociada = camaras.find(c => c.id_camera === t.camera);

                return tipoTicket === 'preventivo' && camaraAsociada?.localidad === loc;
            }).length;
        });

        new Chart(document.getElementById('barChartLocalidad'), {
            type: 'bar',
            data: {
                labels: localidades,
                datasets: [{
                        label: 'Correctivo',
                        data: dataCorrectivo,
                        backgroundColor: '#dc3545'
                    },
                    {
                        label: 'Preventivo',
                        data: dataPreventivo,
                        backgroundColor: '#0dcaf0'
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        stacked: false
                    }
                }
            }
        });
    }
    async function renderDashboard(cameras) {
        try {
            const conteo = {
                'Activa': 0,
                'Inactiva': 0,
                'En Mantenimiento': 0
            };
            cameras.forEach(cam => {
                if (conteo.hasOwnProperty(cam.estado)) {
                    conteo[cam.estado]++;
                }
            });

            const total = cameras.length;
            document.getElementById('total-camaras-text').innerText = `Total de Cámaras: ${total}`;

            // Configuramos la gráfica de dona con los datos
            const ctx = document.getElementById('chartEstadoCamaras').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut', // Usamos un gráfico de dona para la distribución
                data: {
                    labels: ['Activa', 'Inactiva', 'En Mantenimiento'],
                    datasets: [{
                        data: [conteo['Activa'], conteo['Inactiva'], conteo['En Mantenimiento']],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107'], // Verde (activa), Rojo (inactiva), Amarillo (mantenimiento)
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        // Mostramos cantidad y porcentaje cuando pasamos el ratón
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return ` ${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error("Error al cargar el dashboard:", error);
        }
    }
    async function updateKPICards(cameras, tickets) {
        // Calculamos y actualizamos el número de cámaras activas
        const activas = cameras.filter(c => c.estado === 'Activa').length;
        document.getElementById('kpi-activas').innerText = activas;

        // Contamos los tickets en estado nuevo o en curso
        const abiertos = tickets.filter(t => t.estado === 'Nuevo' || t.estado === 'En curso').length;
        document.getElementById('kpi-tickets-abiertos').innerText = abiertos;

        // Calculamos el porcentaje de tickets abiertos que son de alta prioridad
        const criticos = tickets.filter(t => t.prioridad === 'Alta' && t.estado !== 'Resuelto').length;
        const porcentajeCriticos = abiertos > 0 ? ((criticos / abiertos) * 100).toFixed(1) : 0;
        document.getElementById('kpi-criticos').innerText = `${porcentajeCriticos}%`;

        // Calculamos el tiempo promedio que tarda en resolverse un ticket
        const resueltos = tickets.filter(t => t.estado === 'Resuelto' && t.fecha_cierre);
        if (resueltos.length > 0) {
            const sumaDias = resueltos.reduce((acc, t) => {
                const inicio = new Date(t.fecha_apertura);
                const fin = new Date(t.fecha_cierre);
                return acc + (fin - inicio);
            }, 0);
            const promedioDias = (sumaDias / resueltos.length / (1000 * 60 * 60 * 24)).toFixed(1);
            document.getElementById('kpi-promedio').innerText = `${promedioDias}d`;
        } else {
            document.getElementById('kpi-promedio').innerText = "0d";
        }
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .1) !important;
    }

    .bg-light-blue {
        background-color: #f8faff;
    }
</style>

@endsection