@if($node->time == 'h')
    @component('layouts.components.labels.info')
        Orario
    @endcomponent
@else
    @component('layouts.components.labels.success')
        Giornaliero
    @endcomponent
@endif
