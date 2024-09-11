@php
    $action = isset($el) ? '' : route('rapportini.store', ['_type' => 'json']);
    $class = $formClass ?? 'ns';
@endphp

<form class="{{ $class }}" id="frmRapportino" action="{{ $action }}" method="POST" data-callback="{{ $callback ?? null }}">
    @csrf

    @if(isset($items_id))
        <input type="hidden" name="items_id" value="{{ $items_id }}">
    @endif

    @if(isset($reopenForm))
        <input type="hidden" name="reopenForm" value="1">
    @endif

    <div class="modal fade" id="rapportinoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? 'Rapportino'}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.rapportini.forms.create')
                </div>
                @if(!isset($el))
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Conferma</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</form>
