@php
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmChangeStatus" action="{{ $action }}" autocomplete="none" method="post"
      data-callback="$('#modalChangeStatus').modal('hide');$('#refreshOverviewTable').trigger('click');">
    @csrf
    @method('PUT')

    <input type="hidden" name="_type" value="json">
    <input type="hidden" name="_module" value="stato">

    <div class="modal fade" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="modalChangeStatus" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambio stato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">

                        <div class="col-12">
                            <h6>{{ $node->label }}</h6>
                        </div>

                        @component('layouts.components.forms.select', ['name' => 'stato', 'value' => $node->stato, 'elements' => ['in pausa' => 'In pausa', 'avviata' => 'Avviata', 'terminata' => 'Terminata'], 'class' => 'col-md-12'])
                            Stato
                        @endcomponent

{{--                        @component('layouts.components.forms.datetime-picker-single', ['label' => 'Data ora evento', 'name' => 'aaa', 'start' => null, 'end' => null, 'value' => null, 'class' => 'col-md-12'])--}}
{{--                        @endcomponent--}}

                        @component('layouts.components.forms.textarea', ['name' => '_note', 'value' => $node->_note, 'class' => 'col-md-12'])
                            Note
                        @endcomponent

                    </div>

                </div>

                <div class="modal-footer clearfix">
                    <div class="float-right">
                        <button class="btn btn-primary" type="submit">Salva</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
