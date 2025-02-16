<div class="chat-wrapper p-1" id="topic-messages-scroll" style="max-height: 450px; overflow-y: auto">

    @if(count($messages))

        @include('dashboard.topic.forms.messages-stream')

    @else

        @component('layouts.components.alerts.warning')
            Nessun messaggio trovato
        @endcomponent

    @endif
</div>
