@php
    $can_create_rapportini = false;
    if (\Illuminate\Support\Facades\Gate::allows('rapportini_create', $el)) {
        $can_create_rapportini = true;
    }
@endphp

@if($can_create_rapportini)
    <div id="ctx-rapportini" class="btn-group btn-group-sm ctx-menu" style="display: none;">
        <button type="button" data-toggle="modal" data-target="#rapportinoModal" class="btn btn-light">Crea rapportino</button>
    </div>
@endif
