@php
    $action = isset($el) ? route('cliente.update', [$el->id, '_type' => 'json']) : route('cliente.store', ['_type' => 'json']);
@endphp

<form class="ns" id="frmCliente" action="{{ $action }}" method="POST" data-callback="closeAllModal();clienteInsertedCallback();">
    @csrf

    @if(isset($el))
        @method('PUT')
    @endif

    <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? 'Crea nuovo cliente'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-cliente" style="overflow: auto">
                    @include('dashboard.cliente.forms.create')
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </div>
        </div>
    </div>
</form>
