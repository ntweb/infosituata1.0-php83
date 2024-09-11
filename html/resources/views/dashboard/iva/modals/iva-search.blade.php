@php
    $action = route('iva.index');
@endphp

<form class="ns-html" id="frmSearch" action="{{ $action }}" autocomplete="none" method="get" data-container="#div-list-iva" data-callback="ivaSearchCallback();">

    <input type="hidden" name="_search" value="1">

    <div class="modal fade" id="modalIvaSearch" tabindex="-1" role="dialog" aria-labelledby="modalIvaSearch" aria-hidden="true">
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

                        @component('layouts.components.forms.text', ['name' => 'q', 'value' => null, 'class' => 'col-md-12', 'helper' => 'Verrà eseguita una ricerca sui seguenti campi: codice, descrizione, descrizione estesa'])
                            Ricerca
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
