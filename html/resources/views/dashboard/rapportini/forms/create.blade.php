<div class="row">
    <div class="{{ isset($el) ? 'col-12' : 'col-lg-8' }}">
        <h5>Scheda</h5>
        <div class="row">
            @component('layouts.components.forms.text', ['name' => 'titolo', 'value' => @$el->titolo, 'class' => 'col-md-12', '_read_only' => isset($el)])
                Titolo
            @endcomponent

            @component('layouts.components.forms.textarea', ['name' => 'descrizione', 'value' => @$el->descrizione, 'class' => 'col-md-12', 'maxLength' => 100000000, '_read_only' => isset($el)])
                Descrizione
            @endcomponent

            @component('layouts.components.forms.date-native', ['name' => 'start', 'value' => @$el->start, 'class' => 'col-md-4', '_read_only' => isset($el)])
                Data riferimento
            @endcomponent

            @component('layouts.components.forms.radio', ['name' => 'livello', 'value' => @$el->livello ?? 'basso', 'class' => 'col-md-6', 'elements' => ['basso' => 'Basso', 'medio' => 'Medio', 'alto' => 'Alto'], 'inline' => true])
                Livello priorità
            @endcomponent
        </div>
    </div>
    @if(!isset($el))
        <div class="col-lg-4">

            <h5>Inoltro rapportino</h5>

            <div class="row">
                @if(isset($sedi))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'sedi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $sedi, 'elementsSelected' => []])
                        Sedi
                    @endcomponent
                @endif

                @if(isset($gruppi))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => []])
                        Gruppi destinatari
                    @endcomponent
                @endif

                @if(isset($utenti))
                    @component('layouts.components.forms.select2-multiple', ['name' => 'utenti_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $utenti, 'elementsSelected' => []])
                        Destinatari
                    @endcomponent
                @endif

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.checkbox', [ 'name' => 'confirm', 'elements' => [0 => 'Conferma invio del rapportino'], 'value' => 1 ])
                @endcomponent

                <div class="col-12">
                    @component('layouts.components.alerts.warning')
                        Confermando l'invio del rapportino non sarà più possibile modificarlo
                    @endcomponent
                </div>
            </div>
        </div>
    @endif

</div>
