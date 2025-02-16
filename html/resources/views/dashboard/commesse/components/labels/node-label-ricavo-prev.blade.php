@php
    $ricavo = $node->prezzo_cliente - $node->costo_previsto;

    $color = '';
    $style = '';
    if ($node->type == 'fase_lv_1')
        $style = 'background: #e5e5e5; ';
@endphp
@if($node->item_id)

@else

    @php
        if ($ricavo < 0) {
            $icon = 'bxs-down-arrow';
            $color = 'color: red;';
            $style .= $color;
        }
        else if ($ricavo == 0) {
            $icon = 'bx-minus';
            $color = 'color: orange;';
            $style .= $color;
        }
        else {
            $icon = 'bxs-up-arrow';
            $color = 'color: green;';
            $style .= $color;
        }
    @endphp

    @component('layouts.components.labels.default', ['style' => $style])
        {{ euro($ricavo) }} <i class="bx bx-euro ml-1"></i>
    @endcomponent
    <i class="bx {{ $icon }} mr-1" style="{{ $color }} font-size: 10px;"></i>

@endif
