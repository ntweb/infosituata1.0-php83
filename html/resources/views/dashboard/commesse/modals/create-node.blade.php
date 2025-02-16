@php
    $class = 'ns';
    $callback = request()->input('_callback', 'refreshTree();');
    $commessa = isset($el) ? $el->root : $parent->root;
    if (!$commessa) $commessa = $parent;



    $can_modify_fasi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_fasi', $commessa)) {
        $can_modify_fasi = true;
    }

    $can_update_dates = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_date', $commessa)) {
        $can_update_dates = true;
    }

    $can_update_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_costi', $commessa)) {
        $can_update_costi = true;
    }

    $can_view_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_view_costi', $commessa)) {
        $can_view_costi = true;
    }
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); {{ $callback }}">
    @csrf

    @if(isset($el))
        @method('PUT')
    @endif

    <div class="modal fade" id="modalCreateNode" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
        <div class="modal-dialog @if(isset($el)) modal-lg @endif" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">

                        @if(isset($sub_title) && !isset($el))
                            <div class="col text-center">
                                <div class="alert alert-info">
                                    {{ $sub_title }}
                                </div>
                            </div>
                        @endif

                        <div class="@if(isset($el)) col-6 @else col-12 @endif">

                            <h6>Dati generali</h6>

                            <div class="row">

                                @if (!isset($el))
                                    @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true])
                                        Etichetta
                                    @endcomponent
                                @endif

                                @if(isset($el))

                                    @if($can_modify_fasi)

                                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true])
                                            Etichetta
                                        @endcomponent

                                        {{-- @component('layouts.components.forms.select', ['name' => 'time', 'value' => @$el->time, 'elements' => ['h' => 'Orario', 'd' => 'Giornaliero'], 'class' => 'col-md-6'])--}}
                                        {{--    Conteggio--}}
                                        {{-- @endcomponent--}}

                                        @component('layouts.components.forms.color', ['name' => 'color', 'value' => @$el->color, 'class' => 'col-md-4'])
                                            Colore
                                        @endcomponent

                                        @component('layouts.components.forms.select', ['name' => 'execute_after_id', 'value' => @$el->execute_after_id, 'elements' => $siblings, 'class' => 'col-md-8'])
                                            Inizia dopo
                                        @endcomponent

                                    @else

                                        @component('layouts.components.forms.text-static', ['name' => 'Etichetta', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true])
                                            Etichetta
                                        @endcomponent

                                   @endif

                                @endif
                            </div>
                        </div>

                        @if(isset($el))
                            <div class="col-6 border-left">
                                <h6>Dati preventivi</h6>
                                <div class="row">

                                    @if ($el->fl_is_data_prevista_changeble && $can_update_dates)
{{--                                        @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => 'Date inizio e fine previste',  'start' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'end' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString()])--}}
{{--                                        @endcomponent--}}

                                        @component('layouts.components.forms.date-native', ['name' => 'data_inizio_prevista',  'value' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                                            Data inizio prevista
                                        @endcomponent

                                        @component('layouts.components.forms.date-native', ['name' => 'data_fine_prevista',  'value' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6'])
                                            Data fine prevista
                                        @endcomponent
                                    @else
                                        @component('layouts.components.forms.text-static', ['name' => 'Date inizio e fine previste', 'value' => data($el->data_inizio_prevista) .' - '. data($el->data_fine_prevista), 'class' => 'col-md-12', 'helper' => $can_update_dates ? 'Le date previste sono ricavate dalle sottofasi' : null])
                                        @endcomponent
                                    @endif

                                    @if ($can_view_costi || $can_update_costi)
                                        @if ($el->fl_is_costo_changeble && $can_update_costi)
                                            @component('layouts.components.forms.number', ['name' => 'costo_previsto', 'value' => @$el->costo_previsto ? $el->costo_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                                                Costo aziendale
                                            @endcomponent

                                            @component('layouts.components.forms.number', ['name' => 'prezzo_cliente', 'value' => @$el->prezzo_cliente ? $el->prezzo_cliente : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                                                Rivendita al cliente
                                            @endcomponent
                                        @else
                                            @component('layouts.components.forms.text-static', ['name' => 'Costo aziendale', 'value' => euro($el->costo_previsto), 'class' => 'col-md-6', 'helper' => $can_update_costi ? 'Costo ricavato dalle sottofasi' : null])
                                            @endcomponent

                                            @component('layouts.components.forms.text-static', ['name' => 'Rivendita al cliente', 'value' => euro($el->prezzo_cliente), 'class' => 'col-md-6', 'helper' => $can_update_costi ? 'Costo ricavato dalle sottofasi' : null])
                                            @endcomponent
                                        @endif
                                    @endif

                                </div>

                                <hr>
                                <h6>Dati consuntivi</h6>

                                <div class="row">
                                    @if ($el->fl_is_data_prevista_changeble && $can_update_dates)
{{--                                        @component('layouts.components.forms.date-picker-range', ['name' => 'dates_effettive', 'label' => 'Date inizio e fine',  'start' => $el->data_inizio_effettiva ?? null, 'end' => $el->data_fine_effettiva ?? null])--}}
{{--                                        @endcomponent--}}

                                        @component('layouts.components.forms.date-native', ['name' => 'data_inizio_effettiva',  'value' => isset($el) ? $el->data_inizio_effettiva :  null, 'class' => 'col-md-6'])
                                            Data inizio
                                        @endcomponent

                                        @component('layouts.components.forms.date-native', ['name' => 'data_fine_effettiva',  'value' => isset($el) ? $el->data_fine_effettiva :  null, 'class' => 'col-md-6'])
                                            Data fine
                                        @endcomponent
                                    @else
                                        @component('layouts.components.forms.text-static', ['name' => 'Date inizio e fine', 'value' => data($el->data_inizio_effettiva) .' - '. data($el->data_fine_effettiva), 'class' => 'col-md-12', 'helper' => $can_update_dates ? 'Le date sono ricavate dalle sottofasi' : null])
                                        @endcomponent
                                    @endif

                                    @if ($can_view_costi)
                                        @if ($el->fl_is_costo_changeble && $can_update_costi)
                                            @component('layouts.components.forms.number', ['name' => 'costo_effettivo', 'value' => @$el->costo_effettivo ? $el->costo_effettivo : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                                                Costo aziendale
                                            @endcomponent

                                            @component('layouts.components.forms.text', ['name' => 'Costo derivante dai log', 'value' => euro(costoConsuntivoSottofaseLogItem($el)), 'class' => 'col-md-6', '_read_only' => true])
                                                Costo derivante dai log
                                            @endcomponent
                                        @else
                                            @component('layouts.components.forms.text-static', ['name' => 'Costo aziendale', 'value' => euro($el->costo_effettivo), 'class' => 'col-md-6', 'helper' => $can_update_costi ? 'Costo ricavato dalle sottofasi' : null])
                                            @endcomponent
                                        @endif
                                    @endif
                                </div>


                            </div>
                        @endif

                    </div>

                </div>

                @if ($can_modify_fasi)
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-new="0">Salva</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

</form>
