@php
    $action = route('gruppo.users', $el->id);
@endphp

<form class="ns" id="frmSearch" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); location.reload();">
    @csrf
    <div class="modal fade" id="modalAddGroupUser" tabindex="-1" role="dialog" aria-labelledby="modalAddGroupUser" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Aggiungi utenti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        @component('layouts.components.forms.select2-multiple', ['name' => 'utenti', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $utenti, 'elementsSelected' => []])
                            Utenti
                        @endcomponent

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Aggiungi</button>
                </div>
            </div>
        </div>
    </div>
</form>
