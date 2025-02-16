@php
    $action = route('scadenzario.check', [$scadenza->id]);
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmScadenzaCheckControllata" action="{{ $action }}" autocomplete="none" method="post">
    @csrf
    @method('PUT')

    <input type="hidden" name="_new" id="_new" value="0">

    <div class="modal fade" id="modalScadenzaCheckControllata" tabindex="-1" role="dialog" aria-labelledby="modalScadenzaCheckControllata" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Segna scadenza come controllata</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>
                        Si sta segnando la scadenza come <b>controllata</b>.
                        <br>
                        Si desidera crearne una nuova?
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btnCheckScadenza" data-new="1">Salva e crea una nuova scadenza</button>
                    <button type="button" class="btn btn-primary btnCheckScadenza" data-new="0">Salva</button>
                </div>
            </div>
        </div>
    </div>

</form>
