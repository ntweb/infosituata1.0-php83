@php
    $action = route('commessa.print-giornale-lavori', $el->id);
@endphp

<form action="{{ $action }}" autocomplete="none" method="post">
    @csrf

    <div class="modal fade" id="giornaleLavoriModal" tabindex="-1" role="dialog" aria-labelledby="giornbaleLavoriModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Giornale dei lavori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">
                        <div class="col-12">
                            @component('layouts.components.forms.date-native', ['name' => 'date',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-12'])
                                Data/ora inizio
                            @endcomponent
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-new="0">Stampa</button>
                </div>
            </div>
        </div>
    </div>
</form>
