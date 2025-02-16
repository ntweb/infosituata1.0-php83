@php
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmDeleteLog" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#modalDeleteLog').modal('hide'); refreshOverviewTable(); refreshLogsItemTable('{{ route('commessa-node.logs', [$el->commesse_id, '_render_table' => true]) }}')">
    @csrf
    @method('DELETE')

    <div class="modal fade" id="modalDeleteLog" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLog" aria-hidden="true">
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
                        <div class="col text-danger">
                            <p>
                                Si conferma l'eliminazione dell'elemento?
                            </p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" data-new="0">Elimina</button>
                </div>
            </div>
        </div>
    </div>

</form>
