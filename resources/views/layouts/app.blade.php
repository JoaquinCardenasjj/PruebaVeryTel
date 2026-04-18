<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tu ayuda IO')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS propio --}}
    <link href="{{ asset('app.css') }}" rel="stylesheet">

    <meta name="description" content="@yield('meta_description', 'Calculadoras, conversores y herramientas online gratis para negocios, finanzas, estudio y desarrollo.')">
    <meta name="keywords" content="@yield('meta_keywords', 'calculadoras, herramientas online, finanzas, iva, descuentos')">
    <meta name="robots" content="index,follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">

    <link rel="canonical" href="{{ url()->current() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('app.js') }}?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="google-adsense-account" content="ca-pub-6147051544434016">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6147051544434016"
        crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>



<body>



    {{-- Navbar --}}
    @include('components.navbar')

    <div class="container-fluid">
        <div class="row flex-nowrap">
            {{-- SIDEBAR --}}
            <div id="sidebar" class="col-12 col-md-4 col-lg-3 col-xl-2 bg-light min-vh-100 p-3 sidebar">
                @foreach($menuTools as $cat => $tools)
                @php
                // Creamos un ID limpio para el colapso (ej: "vida_diaria" -> "cat-vida-diaria")
                $catId = 'cat-' . Str::slug($cat);
                $firstTool = collect($tools)->first();
                // Verificamos si alguna herramienta de este grupo está activa para dejar el menú abierto
                $isActive = collect($tools)->contains(fn($t, $slug) => Request::is($slug));
                @endphp

                <div class="mb-2">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded"
                        style="cursor: pointer;"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $catId }}"
                        aria-expanded="{{ $isActive ? 'true' : 'false' }}">

                        <div class="d-flex align-items-center">
                            <i class="bi {{ $firstTool['icon'] }} me-2 text-primary" id="icon_main"></i>
                            <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $cat)) }}</strong>
                        </div>

                        <i class="bi bi-chevron-down small transition-icon {{ $isActive ? '' : 'rotate-180' }}"></i>
                    </div>

                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $catId }}">
                        <ul class="nav flex-column ms-3 mt-1 border-start">
                            @foreach($tools as $slug => $tool)
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ Request::is($tool['category']) ? 'active-item' : '' }}"
                                    href="/{{ $tool['link'] }}">
                                    <span class="option_main" style="font-size: 0.9rem;">{{ $tool['name'] }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- CONTENIDO --}}
            <div id="mainContent" class="col-md-9 col-lg-10 p-4 content-area">
                <div class="container text-center mb-4" style="min-height: 60px;">

                </div>
                @yield('content')
                <div class="container text-center mb-4" style="min-height: 60px;">
                </div>
            </div>

        </div>
    </div>



    {{-- Footer --}}
    @include('components.footer')

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    @yield('scripts')

    <script>
        function applySidebarState(isHidden) {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('mainContent');

            if (isHidden) {
                sidebar.classList.add('sidebar-hidden');
                content.classList.add('content-expanded');
            } else {
                sidebar.classList.remove('sidebar-hidden');
                content.classList.remove('content-expanded');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            // Verificamos si actualmente está oculto
            const isHidden = !sidebar.classList.contains('sidebar-hidden');

            applySidebarState(isHidden);
            localStorage.setItem('sidebarHidden', isHidden);
        }

        // Al cargar la página, aplicamos el estado guardado sin animaciones bruscas
        document.addEventListener('DOMContentLoaded', () => {
            const savedState = localStorage.getItem('sidebarHidden') === 'true';
            applySidebarState(savedState);
        });
    </script>

    <div id="cookie-banner" class="fixed-bottom bg-dark text-white p-3 shadow-lg d-none" style="z-index: 9999;">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-9 mb-2 mb-md-0">
                    <small>
                        <i class="bi bi-info-circle me-2"></i>
                        Utilizamos cookies para mejorar tu experiencia en <strong>tuayudaio.com</strong>.
                        Al usar nuestro sitio, aceptas nuestra <a href="/privacidad" class="text-info text-decoration-none">Política de Privacidad</a>.
                    </small>
                </div>
                <div class="col-md-3 text-md-end">
                    <button onclick="acceptCookies()" class="btn btn-primary btn-sm px-4 rounded-pill">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lógica para mostrar/ocultar el banner
        document.addEventListener("DOMContentLoaded", function() {
            if (!localStorage.getItem("cookiesAccepted")) {
                document.getElementById("cookie-banner").classList.remove("d-none");
            }
        });

        function acceptCookies() {
            localStorage.setItem("cookiesAccepted", "true");
            document.getElementById("cookie-banner").classList.add("d-none");
        }
    </script>

</body>

</html>