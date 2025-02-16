@php
    $action = isset($el) ? route('attrezzature.update', [$el->id, '_type' => 'json']) : route('attrezzature.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_module" value="bigtext">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-network icon-gradient bg-love-kiss"> </i>
                Note
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.wysiwyg', ['name' => 'big_extras1', 'value' => @$el->big_extras1, 'class' => 'col-md-12'])
                    Codice HTML
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
