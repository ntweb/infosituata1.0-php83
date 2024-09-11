
<form action="{{ route('rapportini-autorizzazioni.store') }}" class="ns" method="POST">
    @csrf

    <div class="modal fade" id="modalAutorizzazioni" tabindex="-1" role="dialog" aria-labelledby="modalAutorizzazioni" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Autorizzazioni modulo rapportini</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="">
                    <div class="row">
                        <div class="col-12 pb-0">
                            <div id="exampleAccordion" data-children=".item">
                                @foreach($controllers as $key => $c)
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="{{ $key }}1" data-toggle="collapse" href="#{{ $key }}" class="m-0 p-0 btn btn-link">
                                            {{ $c['label'] }} {{ count($gruppiSel[$key]) ? '('.count($gruppiSel[$key]).')' : null }}
                                        </button>
                                        <div data-parent="#exampleAccordion" id="{{ $key }}" class="collapse">
                                            <p class="mb-3">{{ $c['description'] }}</p>
                                            <div class="row">
                                                @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi_ids['.$key.']', 'id' => 'gruppi_ids_'.$key,  'value' => '', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel[$key]])
                                                    Gruppi
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
