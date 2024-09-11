@php
    $action = '#';
    $class = 'ns-payload';
@endphp

<form class="{{ $class }}" id="frmHumanActivityDetail" action="{{ $action }}" autocomplete="none" method="post" data-callback="updateHumanActivityDetail">
    @csrf
    @method('PUT')

    <div class="modal fade" id="modalHumanActivityDetail" tabindex="-1" role="dialog" aria-labelledby="modalHumanActivityDetail" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Human Activity | Dettaglio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4" id="activityDetail"></div>
                        <div class="col-md-8">
                            <div id="map" style="width: 100%; min-height: 500px; height: 100%;"></div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveHumanActivityCheck">Salva come controllato</button>
                </div>
            </div>
        </div>
    </div>

</form>
