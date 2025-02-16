@php
    $action = isset($el) ? route('timbrature-permessi.update', [$el->id, '_type' => 'json']) : route('timbrature-permessi.store');
    $class = isset($el) ? 'ns' : null;

    $dateType = 'date-native';
    if (request()->input('type', '') == 'permesso orario' || old('type', '') == 'permesso orario') {
        $dateType = 'datetime-native';
    }
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @if(request()->has('_user'))
        <input type="hidden" name="_user" value="1">
    @endif

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Permesso
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                <div class="col-md-12">
                    @include('layouts.components.alerts.alert')
                </div>

                @component('layouts.components.forms.select-timbratura-permessi', ['name' => 'type', 'id' => 'selPermessoType',  'value' => @$el->type, 'class' => 'col-md-12'])
                    Tipologia
                @endcomponent

                @component('layouts.components.forms.'.$dateType, ['name' => 'start_at',  'value' => isset($el) ? $el->start_at :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                    Data inizio
                @endcomponent

                @component('layouts.components.forms.'.$dateType, ['name' => 'end_at',  'value' => isset($el) ? $el->end_at :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                    Data fine
                @endcomponent

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.textarea', ['name' => 'note', 'value' => @$el->note, 'class' => 'col-md-12'])
                    Note
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
