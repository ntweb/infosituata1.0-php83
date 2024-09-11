{{--<a href="{{ route('item.edit', $el->id) }}" class="btn btn-sm btn-light ml-1 btn-block">Scheda</a>--}}

@php
    $url = route('infosituata-public.check', [md5($el->id)]);
@endphp
<a href="{{ $url }}" class="btn btn-sm btn-light ml-1 btn-block">Scheda</a>
