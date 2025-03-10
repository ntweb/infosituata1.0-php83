@php
    $style = '';
    if ($node->type == 'fase_lv_1')
        $style = 'background: #e5e5e5';
@endphp

@if($node->item_id || $node->type == 'extra')
    @component('layouts.components.labels.default')
        Log
    @endcomponent
@else

    @if($node->data_inizio_effettiva)
        @component('layouts.components.labels.default', ['style' => $style])
            {{ data($node->data_inizio_effettiva) }}
            -
            {{ data($node->data_fine_effettiva) }}
        @endcomponent
    @else
        @component('layouts.components.labels.warning')
            nd
        @endcomponent
    @endif

@endif

