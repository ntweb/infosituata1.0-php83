@if($node->item_id)

@else

    @if($node->fl_ritardo)
        @component('layouts.components.labels.warning')
            <i class="bx bx-timer mr-1"></i> In ritardo
        @endcomponent
    @endif

@endif
