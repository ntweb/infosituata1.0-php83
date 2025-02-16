<div class="modal fade" id="modalRapportino" tabindex="-1" role="dialog" aria-labelledby="modalRapportino"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $el->titolo }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="node-create-body">
                <div class="row">
                    <div class="col-md-7 pb-0">
                        @include('dashboard.commesse.analisi.components.show-rapportino')
                    </div>
                    <div class="col-md-5 pb-0">
                        @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'commesse_rapportini'])
                            Rapportino
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
