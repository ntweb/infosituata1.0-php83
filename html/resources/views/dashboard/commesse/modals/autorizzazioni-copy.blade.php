<form action="{{ route('commessa-autorizzazioni.copy') }}" class="ns" method="POST" data-callback="closeAllModal();">
    @csrf

    <input type="hidden" name="commesse_root_id" value="{{ $el->id }}">


    <div class="modal fade" id="modalAutorizzazioniCopy" tabindex="-1" role="dialog"
         aria-labelledby="modalAutorizzazioniCopy" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Autorizzazioni copia da</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="">
                    <div class="row">

                        <div class="col-12">
                            @component('layouts.components.alerts.warning')
                                Tutte le autorizzazioni saranno sovrascritte
                            @endcomponent
                        </div>

                        @component('layouts.components.forms.select2-commesse', ['name' => 'copy_commessa_id', 'class' => 'col-md-12', 'value' => null, 'dropdownParent' => '#modalAutorizzazioniCopy'])
                            Copia autorizzazioni da
                        @endcomponent

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        Salva autorizzazioni
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
