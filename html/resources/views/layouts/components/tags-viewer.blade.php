@php
    $tags = explode(',', $slot);
@endphp
@foreach($tags as $tag)
    @component('layouts.components.labels.info')
        {{ $tag }}
    @endcomponent
@endforeach
