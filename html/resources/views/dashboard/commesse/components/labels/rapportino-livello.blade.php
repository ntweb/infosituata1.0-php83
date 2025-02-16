@if($el->livello == 'basso')
    @component('layouts.components.labels.info')
        Basso
    @endcomponent
@elseif($el->livello == 'medio')
    @component('layouts.components.labels.warning')
        Medio
    @endcomponent
@else
    @component('layouts.components.labels.error')
        Alto
    @endcomponent
@endif
