@if($slot == 'annuale')
    @component('layouts.component.labels.info')
        ANNUALE
    @endcomponent
@else
    @component('layouts.component.labels.success')
        GIORNALIERO
    @endcomponent
@endif
