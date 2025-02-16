@php
    $action = route('fattura.index');
@endphp

<form class="ns-html" id="frmSearch" action="{{ $action }}" autocomplete="none" method="get" data-container="#div-list-fattura" data-callback="fatturaSearchCallback();">

    <input type="hidden" name="_search" value="1">

    <div class="modal fade" id="modalFatturaSearch" tabindex="-1" role="dialog" aria-labelledby="modalFatturaSearch" aria-hidden="true">
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

                        Mo vediamo

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Ricerca</button>
                </div>
            </div>
        </div>
    </div>
</form>
