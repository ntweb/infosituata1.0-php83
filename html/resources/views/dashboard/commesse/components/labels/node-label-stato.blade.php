@if($node->item_id)

@else

    @php
        $style = '';
        if ($node->type == 'fase_lv_1' && !$node->fl_is_status_changeble)
            $style = 'background: #e5e5e5';
    @endphp


    @if($node->stato == 'avviata')
        @component('layouts.components.labels.info')
            <i class="bx bx-play mr-1"></i> Avviata
        @endcomponent

    @elseif($node->stato == 'in pausa')
        @component('layouts.components.labels.default')
            <i class="bx bx-pause mr-1"></i> In pausa
        @endcomponent

    @elseif($node->stato == 'terminata')
        @component('layouts.components.labels.success')
            Terminata
        @endcomponent

    @else
        @component('layouts.components.labels.default')
            nd
        @endcomponent
    @endif

@endif
