@php
    $action = route('task.assegnati');
@endphp

<form class="" id="frmSearchTaskAssegnati" action="{{ $action }}" autocomplete="none" method="get" data-container="#div-list-assegnati" data-callback="$('#modalTasksSearch').modal('hide');">

    <input type="hidden" name="_search" value="1">

    <div class="modal fade" id="modalTasksSearch" tabindex="-1" role="dialog" aria-labelledby="modalTasksSearch" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ricerca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        @component('layouts.components.forms.date-native', ['name' => 'start_at', 'id' => 'start_at',  'value' => request('start_at', null), 'class' => 'col-md-6'])
                            Periodo dal
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => 'end_at', 'id' => 'end_at',  'value' => request('end_at', null), 'class' => 'col-md-6'])
                            Periodo al
                        @endcomponent


                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Ricerca</button>
                </div>
            </div>
        </div>
    </div>
</form>
