@php
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); refreshTree();">
    @csrf

    @if(isset($el))
        @method('PUT')
    @endif

    <div class="modal fade" id="modalCreateNode" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
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
                        <div class="col text-center">
                            @if(isset($sub_title) && !isset($el))
                                <div class="alert alert-info">
                                    {{ $sub_title }}
                                </div>
                            @endif
                        </div>

                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true])
                            Etichetta
                        @endcomponent

                        @component('layouts.components.forms.textarea', ['name' => 'description', 'value' => @$el->description, 'class' => 'col-md-12'])
                            Descrizione
                        @endcomponent

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-new="0">Salva</button>
                </div>
            </div>
        </div>
    </div>

</form>
