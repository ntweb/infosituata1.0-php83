<form action="{{ route('commessa-node.extra-field-copy', $el->id) }}" class="ns" method="POST" data-callback="closeAllModal();location.reload();">
    @csrf
    <input type="hidden" name="commesse_root_id" value="{{ $el->id }}">

    <div class="modal fade" id="commessaCopyExtraField" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Copia extra field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p>
                                <small>Questa procedura serve a copiare gli extra field da una commessa all'altra.</small>
                            </p>
                            <p class="text-info bg-light p-2 rounded">
                                Non sarà possibile eseguire la copia se sono già presenti extra field nella commessa.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        @component('layouts.components.forms.select2-commesse', ['name' => 'extra_root_id', 'class' => 'col-md-12', 'value' => null, 'dropdownParent' => '#commessaCopyExtraField'])
                            Copia da commessa
                        @endcomponent
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Esegui copia</button>
                </div>
            </div>
        </div>
    </div>

</form>
