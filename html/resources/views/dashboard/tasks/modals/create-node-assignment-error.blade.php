<div class="modal fade" id="modalCreateNode" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Errore</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="node-create-body">

                <div class="row">
                    <div class="col-12">
                        @component('layouts.components.alerts.error')
                            {{ $error }}
                        @endcomponent
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
