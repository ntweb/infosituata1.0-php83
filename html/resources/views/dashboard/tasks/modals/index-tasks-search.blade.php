@php
    $action = route('task.index');
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
                    <div class="fomr-row">

                        @component('layouts.components.forms.select2-clienti-no-creation', ['name' => 'clienti_id', 'value' => null, 'class' => 'col-md-12', 'dropdownParent' => '#modalTasksSearch'])
                            Cliente
                        @endcomponent


                        @component('layouts.components.forms.tags', ['name' => 'tags', 'value' => null, 'class' => 'col-md-12'])
                            Tags
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
