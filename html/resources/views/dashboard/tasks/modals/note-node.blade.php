@php
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); {{ $callback ?? null }}">
    @csrf

    @method('PUT')

    <input type="hidden" name="_module" value="note">

    <div class="modal fade" id="modalCreateNode" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
        <div class="modal-dialog @if(isset($el)) modal-lg @endif" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">

                        @component('layouts.components.forms.textarea', ['name' => 'note', 'value' => @$node->note, 'class' => 'col-md-12'])
                            Note
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
