@php
    $action = isset($el) ? route('evento.update', [$el->id]) : route('evento.store');
    $class = null;

    $_read_only = false;
    if (isset($el)) {
        if ($el->timbrature_permessi_id) {
            $_read_only = true;
            $action = '#';
        }
    }
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Evento
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.select2-items', ['name' => 'items_id', 'value' => @$el->items_id, 'class' => 'col-md-12', '_read_only' => $_read_only])
                    Associa a
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'titolo', 'value' => @$el->titolo, 'class' => 'col-md-12', '_read_only' => $_read_only])
                    Titolo evento
                @endcomponent

                @component('layouts.components.forms.textarea', ['name' => 'descrizione', 'value' => @$el->descrizione, 'class' => 'col-md-12', '_read_only' => $_read_only])
                    Note
                @endcomponent

                @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => 'Periodo',  'start' => @$el->start ?? \Carbon\Carbon::now()->toDateString(), 'end' => @$el->end ?? \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6', '_read_only' => $_read_only])
                @endcomponent

                @component('layouts.components.forms.radio', ['name' => 'livello', 'value' => @$el->livello ?? 'basso', 'class' => 'col-md-6', 'elements' => ['basso' => 'Basso', 'medio' => 'Medio', 'alto' => 'Alto'], 'inline' => true, '_read_only' => $_read_only])
                    Livello priorità
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        @if(!$_read_only)
            <div class="d-block text-right card-footer">
                <button class="btn btn-primary btn-lg" type="submit">Salva</button>
            </div>
        @endif
    </div>
</form>
