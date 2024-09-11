@php
    $action = isset($el) ? route('whatsapp.update', [$el->id]) : route('whatsapp.store');
    $class = null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" id="_module" value="">

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3 card main-card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon bx bxl-whatsapp icon-gradient bg-love-kiss"> </i>
                        Whatsapp
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">

                        @component('layouts.components.forms.text', ['name' => 'oggetto', 'value' => @$el->oggetto, 'class' => 'col-md-12', 'helper' => 'L\'oggetto serve solo come promemoria, non sarà inviato nel testo el messaggio'])
                            Oggetto
                        @endcomponent

                        @component('layouts.components.forms.textarea', ['name' => 'message', 'value' => '', 'class' => 'col-md-12 p-0 pr-4'])
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
                    <button class="btn btn-primary btn-lg" type="submit">
                        Invia su whatsapp
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="mb-3 card main-card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon bx bx-user icon-gradient bg-love-kiss"> </i>
                        Destinatari
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">

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

                    </div>
                </div>
            </div>

        </div>
    </div>

</form>
