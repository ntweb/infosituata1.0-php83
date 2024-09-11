@php
    $action = isset($el) ? route('commessa.update', [$el->id, '_type' => 'json']) : route('commessa.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Anagrafica
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Etichetta commessa
                @endcomponent

{{--                @component('layouts.components.forms.text-autocomplete', ['name' => 'cliente', 'value' => @$el->cliente, 'route' => route('commessa.clienti'), 'class' => 'col-md-12'])--}}
{{--                    Cliente--}}
{{--                @endcomponent--}}

                @component('layouts.components.forms.select2-clienti', ['name' => 'clienti_id', 'value' => @$el->clienti_id, 'class' => 'col-md-12'])
                    Cliente
                @endcomponent


                @component('layouts.components.forms.text', ['name' => 'protocollo', 'value' => @$el->protocollo, 'class' => 'col-md-12'])
                    Protocollo
                @endcomponent

{{--                @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => 'Date inizio e fine previste',  'start' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'end' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString()])--}}
{{--                @endcomponent--}}

                @component('layouts.components.forms.date-native', ['name' => 'data_inizio_prevista',  'value' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                    Data inizio prevista
                @endcomponent

                @component('layouts.components.forms.date-native', ['name' => 'data_fine_prevista',  'value' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                    Data fine prevista
                @endcomponent

                @component('layouts.components.forms.number', ['name' => 'day_to_hours', 'value' => @$el->day_to_hours ? $el->day_to_hours : 8, 'min' => 0, 'step' => 0.5, 'class' => 'col-md-12'])
                    Ore corrispondenti ad una giornata lavorativa
                @endcomponent

                @component('layouts.components.forms.tags', ['name' => 'tags', 'value' => @$el->tags, 'class' => 'col-md-12'])
                    Tags
                @endcomponent

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.radio', ['name' => 'fl_send_email_association', 'value' => @$el->fl_send_email_association ?? '1', 'class' => 'col-md-12', 'elements' => ['1' => 'Si', '0' => 'No'], 'inline' => true, 'helper' => 'Invia email di notifica agli utenti associati a fasi / sottofasi'])
                    Invia email utenti
                @endcomponent


                @if(!isset($el))
                    <div class="col-12"><hr></div>

                    <div class="col-12">
                        @component('layouts.components.alerts.warning')
                            La scelta del template è obbligatoria al fine di creare una nuova commessa.
                            Una volta creata non sarà più possibile cambiare template.
                        @endcomponent
                    </div>

                    @component('layouts.components.forms.select2-commesse-templates', ['name' => 'commessa_templates_id', 'class' => 'col-md-12', 'value' => null])
                        Template commessa
                    @endcomponent
                @endif

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
{{--            @if(isset($el))--}}
{{--                <a href="javascript:void(0);" class="btn btn-lg btn-light" data-toggle="modal" data-target="#extraFieldModal">Extra field</a>--}}
{{--            @endif--}}
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
