<form action="{{ $action }}" class="ns" method="POST" data-callback="closeAllModal();refreshAvvisi();">
    @csrf

    <div class="modal fade" id="modalAvvisoCommessa" tabindex="-1" role="dialog" aria-labelledby="modalAvvisoCommessa" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuova scadenza</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="checklist-create-body">
                    <div class="row">

                        @component('layouts.components.forms.date-native', ['name' => 'start_at',  'value' => @$el->start_at ?? \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-6', '_read_only' => $readonly])
                            Data evento
                        @endcomponent

                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', '_read_only' => $readonly])
                            Etichetta
                        @endcomponent

                        @component('layouts.components.forms.textarea', ['name' => 'description', 'value' => @$el->description, 'class' => 'col-md-12', '_read_only' => $readonly])
                            Descrizione
                        @endcomponent

                        @if(!$readonly)
                            <div class="col-12">
                                <hr>
                            </div>

                            @component('layouts.components.forms.number-group', ['name' => 'avvisa_entro_gg', 'value' => @$el->avvisa_entro_gg ??  0, 'min' => 0, 'step' => 1, 'group_text' => 'giorni', 'group_align' => 'right', 'class' => 'col-md-6'])
                                Avvisa entro
                            @endcomponent

                            @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel])
                                Avvisa i seguenti gruppi broadcast
                            @endcomponent
                        @endif

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        @if(isset($el))
                            @if($el->checked_at)
                                <small>
                                    Controllato il: {{ dataOra($el->checked_at) }} da {{ $el->checkedBy ? $el->checkedBy->name : 'nd' }}
                                </small>
                            @else
                                <button type="button" class="btn btn-success btnCheckAvviso" data-route="{{ route('scadenzario.check', $el->id) }}">Segna come controllato</button>
                            @endif
                        @endif

                        @if(!$readonly)
                            <button type="submit" class="btn btn-primary">Salva</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
