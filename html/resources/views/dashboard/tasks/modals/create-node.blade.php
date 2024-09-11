@php
    $class = 'ns';
    $callback = request()->input('_callback', 'refreshTree();');
    $mainTask = isset($el) ? $el->root : $parent->root;
    if (!$mainTask) $mainTask = $parent;



    $can_modify_fasi = false;
    if (\Illuminate\Support\Facades\Gate::allows('task_mod_fasi', $mainTask)) {
        $can_modify_fasi = true;
    }

    $can_update_dates = false;
    if (\Illuminate\Support\Facades\Gate::allows('task_mod_date', $mainTask)) {
        $can_update_dates = true;
    }

    $can_update_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('task_mod_costi', $mainTask)) {
        $can_update_costi = true;
    }

    $can_view_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('task_view_costi', $mainTask)) {
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

                                    @else

                                        @component('layouts.components.forms.text-static', ['name' => 'Etichetta', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true])
                                            Etichetta
                                        @endcomponent

                                    @endif

                                @endif

                                @if ($can_update_dates)
                                    @component('layouts.components.forms.datetime-native', ['name' => 'data_inizio_prevista',  'value' => isset($el) ? $el->data_inizio_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-12'])
                                        Data e ora inizio prevista
                                    @endcomponent

                                    @component('layouts.components.forms.datetime-native', ['name' => 'data_fine_prevista',  'value' => isset($el) ? $el->data_fine_prevista :  \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-12'])
                                        Data e ora fine prevista
                                    @endcomponent
                                @else
                                    @component('layouts.components.forms.text-static', ['name' => 'Date inizio e fine previste', 'value' => data($el->data_inizio_prevista) .' - '. data($el->data_fine_prevista), 'class' => 'col-md-12', 'helper' => $can_update_dates ? 'Le date previste sono ricavate dalle sottofasi' : null])
                                    @endcomponent
                                @endif

                                @component('layouts.components.forms.select2-multiple', ['name' => 'users_ids', 'id' => 'users_ids',  'value' => '', 'class' => 'col-md-12', 'elements' => $users, 'elementsSelected' => $usersSelected])
                                    Utenti
                                @endcomponent

                            </div>
                        </div>

                        @if(isset($el))
                            <div class="col-6 border-left">
                                <h6>Dati consuntivi</h6>

                                <div class="row">
                                    @if ($can_update_dates)

                                        @component('layouts.components.forms.datetime-native', ['name' => 'started_at',  'value' => isset($el) ? $el->started_at :  null, 'class' => 'col-md-12'])
                                            Data e ora inizio attività
                                        @endcomponent

                                        @component('layouts.components.forms.datetime-native', ['name' => 'completed_at',  'value' => isset($el) ? $el->completed_at :  null, 'class' => 'col-md-12'])
                                            Data e ora fine attività
                                        @endcomponent
                                    @else
                                        @component('layouts.components.forms.text-static', ['name' => 'Date inizio e fine', 'value' => data($el->started_at) .' - '. data($el->completed_at), 'class' => 'col-md-12', 'helper' => $can_update_dates ? 'Le date sono ricavate dalle sottofasi' : null])
                                        @endcomponent
                                    @endif

                                    @if($el->note)
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            {{ $el->note }}
                                        </div>
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
