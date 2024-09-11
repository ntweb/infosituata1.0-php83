<form id="frmDestroyModal" class="ns-payload" action="" method="post" data-callback="">
    @csrf
    @method('DELETE')
    <div class="modal fade" id="destroyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 10000000 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confermare l'operazione</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 lead">
                        <span id="destroy-message"></span>
                    </p>
                    <p class="mb-0 text-danger">
                        L'operazione sarà irreversibile.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-danger">Conferma</button>
                </div>
            </div>
        </div>
    </div>
</form>
