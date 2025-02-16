@php
    $url = route('infosituata-public.check', [md5($el->id)]);
@endphp
<form class="d-flex align-items-center justify-content-end" style="">
    <a href="{{ $url }}" class="btn btn-sm btn-light ">
        Infosituata
    </a>
    <a href="{{ route('manutenzione.create', ['id' => $el->id]) }}" class="btn btn-sm btn-light ml-1">
        <i class="fa fa-plus"></i> Aggiungi nuova
    </a>
</form>
