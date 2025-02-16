@php
    $action = '#';
    $class = 'ns-payload';
@endphp

<form class="{{ $class }}" id="frmScadenzaCreate" action="{{ $action }}" autocomplete="none" method="post" data-callback="refreshTipologiaScadenze">
    @csrf

    <div class="modal fade" id="modalScadenzaCreate" tabindex="-1" role="dialog" aria-labelledby="modalScadenzaCreate" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuova tipologia di scadenza</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => null, 'class' => 'col-md-12'])
                            Etichetta
                        @endcomponent

                        @component('layouts.components.forms.number', ['name' => 'mesi', 'id' => 'tip_scad_mesi', 'value' => 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-6'])
                            Mesi per prossima scadenza
                        @endcomponent

                        @component('layouts.components.forms.number', ['name' => 'giorni', 'id' => 'tip_scad_giorni', 'value' => 0, 'min' => 0, 'step' => 1, 'class' => 'col-md-6'])
                            Giorni per prossima scadenza
                        @endcomponent

                        <div class="col-12">
                            <small>Attenzione: è possibile scegliere esclusivamente ai fini del calcolo i mesi o i giorni, non entrambi contemporaneamente</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </div>
        </div>
    </div>

</form>
