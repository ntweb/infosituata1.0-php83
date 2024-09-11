<form action="{{ route('commessa-node.massive-copy', $el->id) }}" class="ns" method="POST" data-callback="closeAllModal();location.reload();">
    @csrf
    <input type="hidden" name="commesse_root_id" value="{{ $el->id }}">

    <div class="modal fade" id="commessaMassiveCopyItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Copia massiva di item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p>
                                <small>Questa procedura serve a copiare gli utenti, mezzi, attrezzature da una commessa all'altra.</small>
                                <br>
                                <small>Lo scopo è semplicemente quello di velocizzare la fase di data entry.</small>
                            </p>
                            <p class="text-info bg-light p-2 rounded">
                                Non sarà possibile eseguire la copia se una o più fasi/sottofasi hanno già dichiarazioni di appartenenza di utenti, mezzi o attrezzature.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        @component('layouts.components.forms.select', ['name' => 'item', 'class' => 'col-md-12', 'value' => null, 'elements' => ['' => '-', 'utente' => 'Utenti', 'mezzo' => 'Mezzi', 'attrezzatura' => 'Attrezzature']])
                            Tipologia di item
                        @endcomponent

                        @component('layouts.components.forms.select2-commesse', ['name' => 'root_id', 'class' => 'col-md-12', 'value' => null, 'dropdownParent' => '#commessaMassiveCopyItem'])
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
