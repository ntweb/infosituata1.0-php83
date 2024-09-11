@php
    $action = isset($el) ? route('azienda,update', [$el->id, '_type' => 'json']) : route('azienda,store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="fatturazione">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Dati fiscali
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'piva', 'value' => $el->piva, 'class' => 'col-md-6'])
                    P. IVA
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'codfisc', 'value' => $el->codfisc, 'class' => 'col-md-6'])
                    Cod. Fisc.
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'sdi', 'value' => $el->sdi, 'class' => 'col-md-6'])
                    Cod. SDI
                @endcomponent

                <div class="col-md-12">
                    <hr>
                </div>

                @component('layouts.components.forms.number', ['name' => 'importo', 'value' => $el->importo, 'class' => 'col-md-8', 'min' => 100])
                    Canone annuo
                @endcomponent

                @component('layouts.components.forms.date-picker', ['name' => 'deactivate_at', 'value' => data($el->user->deactivate_at), 'class' => 'col-md-4', 'force_read_only' => true])
                    Data chiusura
                @endcomponent

                <div class="col-md-12">
                    <hr>
                </div>

                @component('layouts.components.forms.wysiwyg', ['name' => 'note', 'value' => @$el->note, 'class' => 'col-md-12'])
                    Note INFOSITUATA
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
                Salva
            </button>
        </div>
    </div>
</form>
