@php
    $action = isset($el) ? route('sms.update', [$el->id, '_type' => 'json']) : route('sms.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" id="_module" value="">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon bx bx-message-alt-detail icon-gradient bg-love-kiss"> </i>
                SMS
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @if(Auth::user()->superadmin)
                    @component('layouts.components.forms.aziende', ['name' => 'azienda_id', 'value' => isset($azienda_id) ? $azienda_id : @$el->azienda_id, 'class' => 'col-md-6'])
                        Azienda
                    @endcomponent
                    <div class="col-md-12"></div>
                @endif

                @if(isset($gruppi))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'sedi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $sedi, 'elementsSelected' => $sediSel])
                        Sedi
                    @endcomponent
                @endif

                @if(isset($gruppi))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel])
                        Gruppi destinatari
                    @endcomponent
                @endif

                @if(isset($utenti))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'utenti_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $utenti, 'elementsSelected' => $utentiSel])
                        Destinatari
                    @endcomponent
                @endif

                @component('layouts.components.forms.text', ['name' => 'oggetto', 'value' => @$el->oggetto, 'class' => 'col-md-12', 'helper' => 'L\'oggetto viene utilizzato per contestualizzare l\'SMS, non sarà inviato con il testo del messaggio'])
                    Oggetto
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'messaggio', 'value' => @$el->messaggio, 'class' => 'col-md-12', 'counter' => true])
                    Messaggio
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            @if(isset($el))
                <button class="btn btn-success btn-lg btnMessageSend" type="submit">Invia SMS</button>
            @endif
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
