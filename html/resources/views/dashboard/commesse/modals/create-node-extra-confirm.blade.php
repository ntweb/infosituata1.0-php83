@php
    $class = 'ns';
    $enableAddButton = true;
    $enableGroupSearch = true;
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal();refreshOverviewTable();">
    @csrf

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

                    <input type="hidden" name="_module" value="extra">

                    <div class="row">
                        <div class="col text-center">
                            @if(isset($sub_title) && !isset($el))
                                <div class="alert alert-info">
                                    {{ $sub_title }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Conferma creazione</button>
                </div>
            </div>
        </div>
    </div>

</form>
