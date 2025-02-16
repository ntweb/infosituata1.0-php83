@php
    $action = isset($el) ? route('task.update', [$el->id, '_type' => 'json']) : route('task.store');
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
                    Etichetta task
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'indirizzo_specifico', 'value' => @$el->indirizzo_specifico, 'class' => 'col-md-12'])
                    Indirizzo specifico
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'note', 'value' => @$el->note, 'class' => 'col-md-12', 'maxLength' => 1500])
                    Note
                @endcomponent

                @component('layouts.components.forms.select2-clienti', ['name' => 'clienti_id', 'value' => @$el->clienti_id, 'class' => 'col-md-12'])
                    Cliente
                @endcomponent

                @component('layouts.components.forms.tags', ['name' => 'tags', 'value' => @$el->tags, 'class' => 'col-md-12'])
                    Tags
                @endcomponent

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.radio', ['name' => 'fl_notify_task_completed', 'value' => @$el->fl_notify_task_completed ?? '1', 'class' => 'col-md-12', 'elements' => ['1' => 'Si', '0' => 'No'], 'inline' => true, 'helper' => 'Invia email di notifica agli utenti associati a fasi / sottofasi'])
                    Invia email a completamento delle attività
                @endcomponent

                @component('layouts.components.forms.select2-multiple', ['name' => 'notify_gruppi_ids', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel])
                    Gruppi destinatari
                @endcomponent

                @if(!isset($el))
                    <div class="col-12"><hr></div>

                    @component('layouts.components.forms.select2-tasks-templates', ['name' => 'tasks_template_id', 'class' => 'col-md-12', 'value' => null])
                        Template task manager
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
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>

