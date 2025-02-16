

    @if($node->started_at && !$node->completed_at)
        @component('layouts.components.labels.info')
            <i class="bx bx-play mr-1"></i> Avviato
        @endcomponent

    @elseif($node->completed_at)
        @component('layouts.components.labels.success')
            <i class="bx bx-pause mr-1"></i> Terminato
        @endcomponent

    @else
        @component('layouts.components.labels.default')
            In attesa
        @endcomponent
    @endif
