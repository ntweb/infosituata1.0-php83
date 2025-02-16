@php
    $url = route('infosituata-public.check', [md5($el->items_id)]);
@endphp
<form class="d-flex align-items-center justify-content-end">
    <a href="{{ $url }}" class="btn btn-sm btn-light ">
        Infosituata
    </a>
</form>
