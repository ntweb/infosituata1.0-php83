@php
    $action = route('task.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">
    @csrf

    <input type="hidden" name="_module" value="fast">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Anagrafica
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Etichetta task
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'indirizzo_specifico', 'value' => @$el->indirizzo_specifico, 'class' => 'col-md-12'])
                    Indirizzo specifico
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'note', 'value' => @$el->note, 'class' => 'col-md-12', 'maxLength' => 1500])
                    Note
                @endcomponent

                @component('layouts.components.forms.datetime-native', ['name' => 'data_inizio_prevista',  'value' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-12'])
                    Data e ora inizio prevista
                @endcomponent

                @component('layouts.components.forms.datetime-native', ['name' => 'data_fine_prevista',  'value' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-12'])
                    Data e ora fine prevista
                @endcomponent

                @component('layouts.components.forms.select2-multiple', ['name' => 'users_ids', 'id' => 'users_ids',  'value' => '', 'class' => 'col-md-12', 'elements' => $users, 'elementsSelected' => []])
                    Utenti
                @endcomponent

                @component('layouts.components.forms.select2-clienti', ['name' => 'clienti_id', 'value' => @$el->clienti_id, 'class' => 'col-md-12'])
                    Cliente
                @endcomponent

                @component('layouts.components.forms.tags', ['name' => 'tags', 'value' => @$el->tags, 'class' => 'col-md-12'])
                    Tags
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>

