<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="btn btn-outline-secondary" id="btn_nav_izq"
        onclick="toggleSidebar()">
        ☰
    </button>
    <div class="container">

        <a class="navbar-brand" href="/index.php">
            <img src="{{ asset('images/logo-tuayuda.png') }}"
                alt="Tu ayuda IO"
                width="40"
                height="40"
                class="me-2">
            Prueba GRUPO VERYTEL S.A.S.
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Inicio</a>
                </li>
            </ul>
        </div>
    </div>
</nav>