@if (session('error') || session('errors'))
    @component('layouts.components.alerts.error')
        {{ session('error') ? session('error') : 'Errore nella compilazione' }}
    @endcomponent
@endif

@if (session('success'))
    @component('layouts.components.alerts.success')
        {{ session('success') }}
    @endcomponent
@endif

@if (session('warning'))
    @component('layouts.components.alerts.warning')
        {{ session('warning') }}
    @endcomponent
@endif

@if (session('info'))
    @component('layouts.components.alerts.info')
        {{ session('info') }}
    @endcomponent
@endif
