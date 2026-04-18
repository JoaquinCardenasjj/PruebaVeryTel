@props(['elementId', 'text' => 'Resultado de mi cálculo: ', 'extraId1' => null, 'extraId2' => null, 'type' => 'general'])

<div class="text-center mt-2">
    <button type="button"
        class="btn btn-outline-success btn-sm rounded-pill px-3 btn-share-whatsapp"
        data-element-id="{{ $elementId }}"
        data-mensaje="{{ $text }}"
        data-extra1="{{ $extraId1 }}"
        data-extra2="{{ $extraId2 }}"
        data-type="{{ $type }}">
        <i class="bi bi-whatsapp me-1"></i> Compartir por WhatsApp
    </button>
</div>