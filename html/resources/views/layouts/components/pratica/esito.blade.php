@if($slot == 'nd')
    @component('layouts.component.labels.default')
        ND
    @endcomponent
@elseif($slot == 'ko')
    @component('layouts.component.labels.error')
        ANNULLATO
    @endcomponent
@else
    @component('layouts.component.labels.success')
        ACCETTATO
    @endcomponent
@endif
