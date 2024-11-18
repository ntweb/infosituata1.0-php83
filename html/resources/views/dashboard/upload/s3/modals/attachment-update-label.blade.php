@php
    $action = '#';
    $class = $formClass ?? 'ns';
@endphp

<form class="{{ $class }}" id="frmAttachmentLabelModal" action="{{ $action }}" method="POST" data-callback="{{ $callback ?? null }}">
    @csrf
    @method('PUT')

    <div class="modal fade" id="updateAttachmentLabelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{'Aggiorna etichetta'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => '', 'class' => 'col-md-12', 'autofocus' => true])
                            Etichetta
                        @endcomponent
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Conferma</button>
                </div>
            </div>
        </div>
    </div>
</form>
