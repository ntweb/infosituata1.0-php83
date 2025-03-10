@php
    $style = '';
    if ($node->type == 'fase_lv_1')
        $style = 'background: #e5e5e5';
@endphp

@if (($field == 'costo_previsto' || $field == 'prezzo_cliente') && ($node->item_id || $node->type == 'extra'))
    {{-- nothing --}}
@else
    @php
        $prezzo = $node->$field;
        if ($node->item_id || $node->type == 'extra') {
            $prezzo = costoConsuntivoLogItem($node);
        }
    @endphp
    @component('layouts.components.labels.default', ['style' => $style])
        {{ euro($prezzo) }} <i class="bx bx-euro ml-1"></i>
    @endcomponent
@endif

