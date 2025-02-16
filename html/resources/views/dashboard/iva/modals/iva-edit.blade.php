@php
    $action = isset($el) ? route('iva.update', [$el->id, '_type' => 'json']) : route('iva.store', ['_type' => 'json']);
@endphp

<form class="ns" id="frmIva" action="{{ $action }}" method="POST" data-callback="closeAllModal();ivaInsertedCallback();">
    @csrf

    @if(isset($el))
        @method('PUT')
    @endif

    <div class="modal fade" id="modalIva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? 'Crea nuova voce'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-iva" style="overflow: auto">
                    @include('dashboard.iva.forms.create')
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </div>
        </div>
    </div>
</form>
