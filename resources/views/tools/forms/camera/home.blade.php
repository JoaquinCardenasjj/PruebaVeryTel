<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4 text-primary d-flex align-items-center">
                    <i class="bi bi-percent me-2"></i> Regla de Tres Simple
                </h4>

                <p class="text-muted small mb-4">Si **A** es a **B**, ¿Cuánto es **C** a **X**?</p>

                <div class="row g-3 align-items-center text-center">
                    <div class="col-5">
                        <label class="form-label small">Valor A</label>
                        <input type="number" id="valor_a" class="form-control form-control-lg text-center" placeholder="A">
                    </div>
                    <div class="col-2">
                        <i class="bi bi-arrow-right h4 text-muted"></i>
                    </div>
                    <div class="col-5">
                        <label class="form-label small">Valor B</label>
                        <input type="number" id="valor_b" class="form-control form-control-lg text-center" placeholder="B">
                    </div>

                    <div class="col-5">
                        <label class="form-label small">Valor C</label>
                        <input type="number" id="valor_c" class="form-control form-control-lg text-center" placeholder="C">
                    </div>
                    <div class="col-2">
                        <i class="bi bi-arrow-right h4 text-muted"></i>
                    </div>
                    <div class="col-5">
                        <label class="form-label small fw-bold text-primary">Resultado (X)</label>
                        <div class="form-control form-control-lg bg-light fw-bold text-primary" id="res-total" style="min-height: 48px;">?</div>
                    </div>

                    <div class="col-12 mt-4">
                        <button onclick="calcularReglaTres()" class="btn btn-primary btn-lg w-100 shadow-sm">
                            <i class="bi bi-magic me-1"></i> Resolver
                        </button>
                    </div>
                </div>

                {{-- Contenedor de Resultados (Botones) --}}
                <div id="resultado-container" class="d-none mt-4 animate__animated animate__fadeIn">
                    <hr>
                    <div class="text-center mt-4 d-flex justify-content-center">
                        <button class="btn btn-link btn-sm text-decoration-none" onclick="copiarTotal()">
                            <i class="bi bi-clipboard me-1"></i> Copiar
                        </button>
                    </div>

                    <div class="text-center mt-2 d-flex justify-content-center">
                        <x-share-whatsapp
                            elementId="res-total"
                            text="Resolví esta regla de tres rápidamente: "
                            extraId1="valor_a"
                            type="regla_tres" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-3 bg-white rounded shadow-sm border-start border-primary border-4">
            <p class="mb-0 small text-muted">
                <strong>💡 Ejemplo:</strong> Si 2 kilos de manzanas cuestan $10.000 (A y B), ¿cuánto cuestan 5 kilos (C)? Pon 2 en A, 10.000 en B, 5 en C y obtendrás el resultado en X.
            </p>
        </div>
    </div>
</div>