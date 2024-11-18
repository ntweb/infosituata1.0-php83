@php
    $action = route('topic.index');
@endphp

<form class="" id="frmSearchTopic" action="{{ $action }}" autocomplete="none" method="get" data-container="#div-list-topic" data-callback="$('#modalTopicSearch').modal('hide');">

    <input type="hidden" name="_search" value="1">

    <div class="modal fade" id="modalTopicSearch" tabindex="-1" role="dialog" aria-labelledby="modalTopicSearch" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ricerca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        @component('layouts.components.forms.text', ['name' => '_search', 'value' => '', 'class' => 'col-md-12'])
                            Ricerca
                        @endcomponent

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Ricerca</button>
                </div>
            </div>
        </div>
    </div>
</form>
