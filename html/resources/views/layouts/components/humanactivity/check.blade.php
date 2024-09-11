@php
    $slot = $el->checked_at;
    $animation = true;
    $animation = $el->checked_at ? false : $animation;

    $route = route('human-activity.show', [$el->id]);
    $update = route('human-activity.update', [$el->id]);
    $latitude = $el->latitude;
    $longitude = $el->longitude;
@endphp

<button id="btnOpenHumanActivityDetail-{{ $el->id }}" class="border-0 btn-transition btn btn-sm btn-outline-success btnOpenHumanActivityDetail" data-route="{{ $route }}" data-update="{{ $update }}" data-latitude="{{ $latitude }}" data-longitude="{{ $longitude }}" data-save-btn="hide" style="{{ $slot ? null : 'display: none;' }}">
    Checked <i class="fas fa-check"></i>
</button>

@if(!$slot)
<button class="border-0 btn-transition btn btn-sm btn-warning {{ $animation ? 'animated heartBeat infinite' : null }} btnOpenHumanActivityDetail" data-route="{{ $route }}" data-update="{{ $update }}" data-latitude="{{ $latitude }}" data-longitude="{{ $longitude }}" data-save-btn="show">
    Not checked
</button>
@endif
