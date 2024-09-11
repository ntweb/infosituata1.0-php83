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

                        @if (!isset($el))
                            @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true, "readonly" => true])
                                Etichetta
                            @endcomponent
                        @endif

                        @if(isset($el))

                            @if($el->item_id)
                                <div class="col-12">
                                    <h5>{{ $el->label }}</h5>
                                </div>
                            @else
                                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12', 'autofocus' => true, "readonly" => true])
                                    Etichetta
                                @endcomponent
                            @endif

                            {{-- Momentaneamente disabilitato--}}
                            {{--    @component('layouts.components.forms.select', ['name' => 'time', 'value' => @$el->time, 'elements' => ['h' => 'Orario', 'd' => 'Giornaliero'], 'class' => 'col-md-6'])--}}
                            {{--        Conteggio--}}
                            {{--    @endcomponent--}}

                            @if(!$el->item_id)
                                @component('layouts.components.forms.color', ['name' => 'color', 'value' => @$el->color, 'class' => 'col-md-4'])
                                    Colore
                                @endcomponent

                                @component('layouts.components.forms.select', ['name' => 'execute_after_id', 'value' => @$el->execute_after_id, 'elements' => $siblings, 'class' => 'col-md-8'])
                                    Inizia dopo
                                @endcomponent
                            @endif

                        @endif
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-new="0">Salva</button>
                </div>
            </div>
        </div>
    </div>

</form>
