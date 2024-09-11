@php
    $action = isset($el) ? route('timbrature-permessi.update', [$el->id, '_type' => 'json']) : route('timbrature-permessi.store');
    // $class = isset($el) ? 'ns' : null;
    $class = 'ns';

    $dateType = 'date-native';
    if (request()->input('type', '') == 'permesso orario' || old('type', '') == 'permesso orario') {
        $dateType = 'datetime-native';
    }

    if (isset($el)) {
        $dateType = 'date-native';
        if ($el->type == 'permesso orario') {
            $dateType = 'datetime-native';
        }

        if ($dateType == 'date-native') {
            $el->start_at = substr($el->start_at, 0, 10);
            $el->end_at = substr($el->end_at, 0, 10);
        }
    }
@endphp

<form class="{{ $class }}" id="frmChangeStatus" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#modalPermesso').modal('hide'); refreshPermessiTable();">
    @csrf

    @if(isset($el))
        @method('PUT')
    @else
        <input type="hidden" name="_power_user" value="1">
        <input type="hidden" name="_type" value="json">
    @endif

    <div class="modal fade" id="modalPermesso" tabindex="-1" role="dialog" aria-labelledby="modalPermesso" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permesso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="checklist-create-body">
                    <div class="row">

                        @if(isset($el))
                            <div class="col-12">
                                <h6>{{ $el->user->name }}</h6>
                            </div>
                        @else
                            @component('layouts.components.forms.select2-users', ['name' => 'users_id', 'value' => null, 'class' => 'col-md-12'])
                                Utente
                            @endcomponent

                        @endif

                        @component('layouts.components.forms.select-timbratura-permessi', ['name' => 'type', 'id' => 'selPermessoType',  'value' => @$el->type, 'class' => 'col-md-12', '_read_only' => isset($el)])
                            Tipologia
                        @endcomponent

                        @component('layouts.components.forms.'.$dateType, ['name' => 'start_at',  'value' => isset($el) ? $el->start_at :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6', '_read_only' => isset($el)])
                            Data inizio
                        @endcomponent

                        @component('layouts.components.forms.'.$dateType, ['name' => 'end_at',  'value' => isset($el) ? $el->end_at :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6', '_read_only' => isset($el)])
                            Data fine
                        @endcomponent

                        @if(isset($el))
                            @if($el->note)
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <strong>Note utente</strong>
                                    <br>
                                    <p>
                                        {!! $el->note !!}
                                    </p>
                                </div>
                            @endif
                        @endif

                        <div class="col-12">
                            <hr>
                        </div>

                        @component('layouts.components.forms.textarea', ['name' => 'note_office', 'value' => @$el->note_office, 'class' => 'col-md-12'])
                            Note operatore uff. personale
                        @endcomponent

                        @component('layouts.components.forms.radio', ['name' => 'status', 'value' => @$el->status, 'class' => 'col-md-12', 'elements' => ['accettato' => 'Accettato', 'rifiutato' => 'Rifiutato'], 'inline' => true])
                            Stato permesso
                        @endcomponent


                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Salva</button>
                </div>
            </div>
        </div>
    </div>
</form>
