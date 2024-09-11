

@php
    $action = route('timbrature.store');
    $class = null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">



    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">
    <input type="hidden" id="type" name="type">



    <div id="timbratura-error-geolocation" style="display: none">
        <div class="alert alert-danger">
            <h4>Errore!</h4>
            <p id="timbratura-error-geolocation-message">Per timbrare bisogna consentire al browser di utilizzare la
                geolocalizzazione.</p>
        </div>
    </div>


    <div class="mb-3 card main-card" id="timbratura-card" style="display: none">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Nuova timbratura
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                   <div id="map" class="flex-fill" style="min-height: 300px;"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-row d-flex flex-column align-items-center justify-content-start">
                        <div class="mt-4 mb-4">
                            <p class="text-center fsize-2 mb-0">
                                {{ data(\Carbon\Carbon::today()) }}
                            </p>
                            <p class="text-center" style="font-size: 80px">
                                <span id="countdown" data-hms="{{ \Carbon\Carbon::now()->timestamp }}">hh:mm:ss</span>
                            </p>
                        </div>


                            @if ($commesse->count())
                                @component('layouts.components.forms.select2-commesse-timbrature', ['name' => 'commesse_id', 'value' => null, 'class' => 'col-md-12'])
                                    Commessa
                                @endcomponent
                            @endif

                        <div class="mb-4">
                            <button type="button" id="btnTimbraIngresso" class="btn btn-success btn-lg mr-2">
                                Timbra ingresso
                            </button>
                            <button type="button" id="btnTimbraUscita" class="btn btn-light btn-lg">
                                Timbra uscita
                            </button>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    @if(!$timbrature->count())
                        @component('layouts.components.alerts.warning')
                            Nessuna timbratura presente per la giornata in corso
                        @endcomponent
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Ora timbratura</th>
                                    <th></th>
                                    <th class="text-right"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($timbrature as $t)
                                    <tr>
                                        <td>{{ ora($t->marked_at) }}</td>
                                        <td>{{ $t->commesse_label }}</td>
                                        <td class="text-right">
                                            @component('layouts.components.timbratura.verso', ['timbratura' => $t])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</form>
