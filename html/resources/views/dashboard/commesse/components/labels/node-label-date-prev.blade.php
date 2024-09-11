@php
    $style = '';
    if ($node->type == 'fase_lv_1')
        $style = 'background: #e5e5e5';
@endphp

@if($node->data_inizio_prevista)
    @component('layouts.components.labels.default', ['style' => $style])
        {{ data($node->data_inizio_prevista) }}
        -
        {{ data($node->data_fine_prevista) }}
    @endcomponent
@else
    @component('layouts.components.labels.warning')
        nd
    @endcomponent
@endif
