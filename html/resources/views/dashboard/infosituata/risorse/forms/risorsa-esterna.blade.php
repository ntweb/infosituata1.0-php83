@php
    $action = isset($el) ? route('risorse.update', [$el->id, '_type' => 'json']) : route('risorse.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="risorsa_esterna">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-global icon-gradient bg-love-kiss"> </i>
                Risorsa esterna
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'extras2', 'value' => @$el->extras2, 'placeholder' =>'Es: http://www.something.it',  'class' => 'col-md-12'])
                    Url esterno
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
