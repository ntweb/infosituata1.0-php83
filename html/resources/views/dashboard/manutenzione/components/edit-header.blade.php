@php
    $url = route('infosituata-public.check', [md5($el->items_id)]);
@endphp
<form class="d-flex align-items-center justify-content-end" style="">
    <a href="{{ $url }}" class="btn btn-sm btn-light ">
        Infosituata
    </a>
    <a
        href="#" class="btn btn-sm btn-light ml-1  get-html"
        data-route="{{ route('manutenzione-dettaglio.create', ['id' => $el->id]) }}"
        data-container="#form-create">
        <i class="fa fa-plus"></i> Inserisci dettaglio
    </a>
</form>
