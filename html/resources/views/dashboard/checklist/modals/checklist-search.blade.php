@php
    $action = route('checklist.index');
@endphp

<form class="ns-html" id="frmSearch" action="{{ $action }}" autocomplete="none" method="get" data-container="#div-list-checklist" data-callback="$('#modalChecklistSearch').modal('hide');">

    <input type="hidden" name="_search" value="1">

    <div class="modal fade" id="modalChecklistSearch" tabindex="-1" role="dialog" aria-labelledby="modalChecklistSearch" aria-hidden="true">
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

                        @component('layouts.components.forms.select2-users', ['name' => 'search[users_id]', 'id' => 'users_id', 'value' => null, 'class' => 'col-md-12', 'dropdownParent' => '#modalChecklistSearch'])
                            Redatta da
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => 'search[start_at]', 'id' => 'start_at',  'value' => null, 'class' => 'col-md-6'])
                            Data inizio creazione
                        @endcomponent

                        @component('layouts.components.forms.date-native', ['name' => 'search[end_at]', 'id' => 'end_at',  'value' => null, 'class' => 'col-md-6'])
                            Data fine creazione
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
