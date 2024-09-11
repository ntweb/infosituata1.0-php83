@php
    $slot = trim(strtolower($el->stress_level));
    $animation = true;
    $animation = $el->checked_at ? false : $animation;
@endphp

@if($slot == 'normal')
    <div class="badge badge-success">Normal</div>
@elseif($slot == 'warning')
    <div class="badge badge-warning {{ $animation ? 'animated heartBeat infinite' : null }}">
        <i class="fas fa-exclamation"></i>
        Warning
    </div>
@elseif($slot == 'critical')
    <div class="badge badge-danger {{ $animation ? 'animated heartBeat infinite' : null }}">
        <i class="fas fa-exclamation"></i>
        Critical
    </div>
@else
    <div class="badge badge-light">No data</div>
@endif
