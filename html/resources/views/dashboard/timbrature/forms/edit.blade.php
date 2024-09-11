@php
    $action = route('timbrature.store');
    $class = null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <input type="hidden" name="_users_id" value="{{ $user->id }}">
    <input type="hidden" name="_marked_at" value="{{ $date ? strToDate($date)->toDateString() : null }}">

    <div class="mb-3 card main-card" >
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                {{ $user->name }}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-row text-center">
                        <div class="mt-4 mb-4">
                            <p class="text-center mb-0" style="font-size: 40px">
                                {{ $date }}
                            </p>
                        </div>
                    </div>
                    <div class="row">

                        @component('layouts.components.forms.time', ['name' => '_time', 'value' => null, 'class' => 'col-md-6'])
                            Ora evento
                        @endcomponent

                        @component('layouts.components.forms.select', ['name' => 'type', 'value' => null, 'elements' => ['in' => 'Ingresso', 'out' => 'Uscita'], 'class' => 'col-md-6'])
                            Verso
                        @endcomponent


{{--                        @component('layouts.components.forms.select2-commesse-timbrature', ['name' => 'commesse_id', 'value' => null, 'class' => 'col-md-12', 'id_utente' => $user->utente_id, 'data' => strToDate($date)->toDateString()])--}}
{{--                            Commessa--}}
{{--                        @endcomponent--}}

                        <div class="col-md-12">
                            <button class="btn btn-success">Inserisci</button>
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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-right"></th>
                                        <th></th>
                                        <th>Timb.</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timbrature as $t)
                                        <tr id="l-{{ $t->id  }}">
                                            <td class="text-right" style="width: 20%">
                                                @component('layouts.components.timbratura.verso', ['timbratura' => $t])
                                                @endcomponent
                                            </td>
                                            <td class="text-center timbraturaMapPosition" @if($t->latitude) style="cursor: pointer" data-latitude="{{ $t->latitude }}" data-longitude="{{ $t->longitude }}" @endif>
                                                @if($t->latitude)
                                                    <i class="fa fa-map-marker-alt"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{ ora($t->marked_at) }}
                                                @if($t->updated_by)
                                                <i class="fa fa-edit ml-2"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $t->commesse_label }}</small>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-danger btnDelete btn-sm"
                                                        data-message="Si conferma la cancellazione?"
                                                        data-route="{{ route('timbrature.destroy', [$t->id, '_type' => 'json']) }}"
                                                        data-callback="deleteElement('#l-{{ $t->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 d-flex">
                    <div id="map" class="flex-fill" style="min-height: 200px"></div>
                </div>
            </div>

        </div>
    </div>
</form>
