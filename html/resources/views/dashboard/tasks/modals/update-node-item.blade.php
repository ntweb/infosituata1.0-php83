@php
    $class = 'ns';
    $commessa = $el->root;

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

    $can_risorse_create = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_create', $commessa)) {
        $can_risorse_create = true;
    }
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); refreshTree(); refreshOverviewTable();">
    @csrf

    @if(isset($el))
        @method('PUT')
    @endif

    <div class="modal fade" id="modalCreateNode" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
        <div class="modal-dialog" role="document">
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

                        <div class="col-12">

                            <h6>Dati generali</h6>

                            <div class="row">

                                <div class="col-12">
                                    <h6>{{ $el->label }}</h6>
                                    <hr>
                                </div>

{{--                                @component('layouts.components.forms.select', ['name' => 'time', 'value' => @$el->time, 'elements' => ['h' => 'Orario', 'd' => 'Giornaliero'], 'class' => 'col-md-6'])--}}
{{--                                    Conteggio--}}
{{--                                @endcomponent--}}

                                @if($el->type != 'materiale')
                                    @if ($el->fl_is_data_prevista_changeble && $can_update_dates)
{{--                                        @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => 'Date inizio e fine previste',  'start' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->startOfDay(), 'end' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->endOfDay()])--}}
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
                                @endif

                                @if($can_view_costi)
                                    @if($el->type == 'materiale')
                                        @if ($can_update_costi)
                                            @component('layouts.components.forms.number', ['name' => 'costo_previsto', 'value' => @$el->costo_previsto ? $el->costo_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                                                Costo aziendale
                                            @endcomponent
                                        @else
                                            @component('layouts.components.forms.text-static', ['name' => 'Costo aziendale', 'value' => @$el->costo_previsto ? $el->costo_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
                                                Costo aziendale
                                            @endcomponent
                                        @endif
                                    @else
                                        @if ($can_update_costi)
                                            @component('layouts.components.forms.number', ['name' => 'costo_item_giornaliero_previsto', 'value' => @$el->costo_item_giornaliero_previsto ? $el->costo_item_giornaliero_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01, 'data' => 'data-day-to-hours='.$el->day_to_hours, 'readonly' => true])
                                                Costo aziendale / giornaliero
                                            @endcomponent
                                            @component('layouts.components.forms.number', ['name' => 'costo_item_orario_previsto', 'value' => @$el->costo_item_orario_previsto ? $el->costo_item_orario_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01, 'data' => 'data-day-to-hours='.$el->day_to_hours])
                                                Costo aziendale / orario
                                            @endcomponent
                                        @else
                                            @component('layouts.components.forms.text-static', ['name' => 'Costo aziendale / giornaliero', 'value' => @$el->costo_item_giornaliero_previsto ? $el->costo_item_giornaliero_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01, 'data' => 'data-day-to-hours='.$el->day_to_hours, 'readonly' => true])
                                                Costo aziendale / giornaliero
                                            @endcomponent
                                            @component('layouts.components.forms.text-static', ['name' => 'Costo aziendale / orario', 'value' => @$el->costo_item_orario_previsto ? $el->costo_item_orario_previsto : 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01, 'data' => 'data-day-to-hours='.$el->day_to_hours])
                                                Costo aziendale / orario
                                            @endcomponent
                                        @endif
                                    @endif
                                @endif

                                <div class="col-12">
                                    <hr>
                                </div>

                                @component('layouts.components.forms.textarea', ['name' => 'note', 'value' => @$el->note, 'class' => 'col-md-12'])
                                    Note
                                @endcomponent

                            </div>
                        </div>
                    </div>

                </div>
                @if($can_risorse_create)
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-new="0">Salva</button>
                </div>
                @endif
            </div>
        </div>
    </div>

</form>
