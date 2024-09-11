@php
    $slot = trim(strtolower($el->alert))
@endphp

@if($slot != '')
    <div class="badge @if($slot == 'manual') badge-success @else badge-light @endif">{{ Str::title($slot) }}</div>
@else
    <div class="badge badge-light">No data</div>
@endif
