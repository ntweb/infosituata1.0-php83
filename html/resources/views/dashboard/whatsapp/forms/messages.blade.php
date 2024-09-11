<div class="chat-wrapper p-1" id="whatsapp-messages-scroll" style="max-height: 450px; overflow-y: auto">

    @if(count($messages))

        @include('dashboard.whatsapp.forms.messages-stream')

    @else

        <div class="col-12">
            @component('layouts.components.alerts.warning')
                Nessun messaggio trovato
            @endcomponent
        </div>

    @endif
</div>
