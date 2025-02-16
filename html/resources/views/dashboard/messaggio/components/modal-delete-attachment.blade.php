@php
    $action = '#';
    $class = 'ns-payload';
@endphp

<form class="{{ $class }}" id="frmDeleteAttachment" action="{{ $action }}" autocomplete="none" method="post" data-callback="refreshAttachmentTable">
    @csrf

    <div class="modal fade" id="modalDeleteAttachment" tabindex="-1" role="dialog" aria-labelledby="modalScadenzaCreate" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancellazione allegato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    Attenzione! Procedendo con la cancellazione saranno persi tutti i dati relativi al file.

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Procedi con la cancellazione</button>
                </div>
            </div>
        </div>
    </div>

</form>
