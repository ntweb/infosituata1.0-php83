@php
    $action = route('timbrature.store');
    $class = null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_admin" value="1">


    <div class="mb-3 card main-card" >
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Crea nuova timbratura
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="row">

                        @component('layouts.components.forms.select2-users', ['name' => '_users_id', 'value' => null, 'class' => 'col-md-12'])
                            Utente
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => '_marked_at', 'value' => null, 'class' => 'col-md-12'])
                            Data timbratura
                        @endcomponent

                        @component('layouts.components.forms.time', ['name' => '_time', 'value' => null, 'class' => 'col-md-6'])
                            Ora
                        @endcomponent

                        @component('layouts.components.forms.select', ['name' => 'type', 'value' => null, 'elements' => ['in' => 'Ingresso', 'out' => 'Uscita'], 'class' => 'col-md-6'])
                            Verso
                        @endcomponent

                        @if (Gate::allows('is-commesse-module-enabled'))
                            <div id="commessa" class="w-100" data-route="{{ route('timbrature.refresh-select-commesse') }}"></div>
                        @endif

                        <div class="col-md-12">
                            <button class="btn btn-success">Inserisci</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
