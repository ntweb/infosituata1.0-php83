@php
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="frmCreateItem" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#modalCreateItem').modal('hide');searchAftertInsert('{{ request()->input('_module') }}');">
    @csrf

    <input type="hidden" name="_type" value="json">

    <div class="modal fade" id="modalCreateItem" tabindex="-1" role="dialog" aria-labelledby="modalCreateItem" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">

                        @if(isset($sub_title) && !isset($el))
                            <div class="col text-center">
                                <div class="alert alert-info">
                                    {{ $sub_title }}
                                </div>
                            </div>
                        @endif

                        @include($item_store_view)

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
