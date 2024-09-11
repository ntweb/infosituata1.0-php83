<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modalNote" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="node-create-body">

                <div class="row">
                    <div class="col-12">
                        <p>
                            {{ $el->note }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Chiudi</button>
            </div>

        </div>
    </div>
</div>
