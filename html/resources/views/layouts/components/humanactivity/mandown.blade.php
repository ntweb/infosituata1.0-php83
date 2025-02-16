@php
    $slot = trim(strtolower($el->man_down));
    $animation = true;
    $animation = $el->checked_at ? false : $animation;
@endphp

@if($slot == 'up')
    <div class="badge badge-success">
        <i class="fas fa-running"></i>
        {{ Str::title($slot) }}
    </div>
@elseif($slot == 'down')
    <div class="badge badge-danger {{ $animation ? 'animated heartBeat infinite' : null }}">
        <i class="fas fa-running"></i>
        {{ Str::title($slot) }}
    </div>
@else
    <div class="badge badge-light">No data</div>
@endif
