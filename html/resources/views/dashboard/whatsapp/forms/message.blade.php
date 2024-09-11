@php
    $action = route('whatsapp.store-message', $el->id);
    $refreshUrl = route('whatsapp.messages', $el->id);
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="whatsapp" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#message').val(null);reloadWhatsapp('{{ $refreshUrl }}');" data-refresh-url="{{ $refreshUrl }}">

    @csrf

    <input type="hidden" name="_module" id="_module" value="">

    <div class="app-inner-layout__content card">
        <div class="table-responsive">
            <div class="app-inner-layout__top-pane">
                <div class="pane-left">
                    <div class="p-2 px-4">
                        <h4 class="mb-0 text-nowrap">{{ title_case(strtolower($el->label)) }}</h4>
                    </div>
                </div>
            </div>

            <div id="messages"></div>

            <div class="d-block text-center overflow-hidden p-4">
                <div class="row form-group">
                    <div class="col-sm-12">
                        <input placeholder="Invia messaggio..."
                               type="text"
                               class="form-control-lg form-control"
                               id="whatsappMessageText"
                               data-refresh-button="#btnLoadWhatsappMessages{{$utente_id}}"
                               data-route="{{ route('whatsapp.send', ['utente_id' => $utente_id]) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
