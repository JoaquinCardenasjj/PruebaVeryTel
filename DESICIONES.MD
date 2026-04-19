1. Arquitectura de Conectividad: Patrón API Gateway / Proxy
Decisión: Se implementó un Proxy en Laravel para realizar las peticiones al backend de Django.

Justificación: * Seguridad: Permite ocultar la URL real del backend y las credenciales de la API al cliente final.

CORS: Evita problemas de Cross-Origin Resource Sharing al centralizar las peticiones en el mismo dominio.

Centralización: Facilita la implementación de un middleware de seguridad (CSRF) antes de enviar datos sensibles al servidor de Python.

2. Framework Frontend: Laravel Blade + JavaScript Vanilla
Decisión: Uso de Blade para la estructura y JavaScript puro para la interactividad.

Justificación: * Simplicidad y Velocidad: Al ser una prueba técnica, el uso de Blade permite un renderizado inicial rápido 
(SEO friendly) sin la sobrecarga de configurar un entorno complejo de SPA (como React o Vue) desde cero.

Reactividad ligera: Para el Dashboard, JavaScript Vanilla es suficiente para manipular el DOM y las gráficas, 
manteniendo el bundle del proyecto liviano.

3. Visualización de Datos: Chart.js
Decisión: Selección de Chart.js para gráficas de dona y barras.

Justificación:

Versatilidad: Ofrece soporte nativo para gráficas responsivas.

Personalización: Permite calcular porcentajes dinámicos en los tooltips mediante callbacks, 
cumpliendo con el requerimiento de mostrar cantidad y porcentaje simultáneamente.

Rendimiento: El procesamiento de datos (contar tipos de tickets o estados de cámaras) 
se realiza en el cliente para reducir la carga de procesamiento en el servidor de Django.

4. Geolocalización: Leaflet.js
Decisión: Uso de Leaflet.js sobre Google Maps.

Justificación:

Open Source: No requiere llaves de API ni cuotas de consumo, lo que lo hace ideal para despliegues rápidos y auditorías.

Personalización: Facilita la creación de CircleMarkers con colores dinámicos (Verde/Amarillo/Rojo)
 según el estado de la cámara y la integración de popups enriquecidos con datos cruzados de tickets.

5. Manejo de Datos y Paginación
Decisión: Solicitud de datos mediante parámetros de page_size=100 y desactivación de caché en el proxy.

Justificación: * Integridad del Dashboard: Para que las métricas (KPIs) y el mapa sean veraces, se requiere el set completo de datos. Se optó por una carga masiva inicial controlada para evitar múltiples peticiones por página que degradarían la experiencia del usuario.
 5. Tiempo Invertido (Estimado)
 MóduloTiempo EstimadoConfiguración de entorno: 1 hora
 CRUD Cámaras y Soft Delete 1.5 horas
 CRUD Tickets y Lógica de Estados 1 hora
 Reportes y KPIs 1 hora
 Total 4.5 horas
 Publicacion codigo backend 0.5 hora
 
	  
