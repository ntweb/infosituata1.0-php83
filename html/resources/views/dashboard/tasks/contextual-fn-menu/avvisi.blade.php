@php
    $can_create_avvisi = false;
    if (\Illuminate\Support\Facades\Gate::allows('avvisi_create', $el)) {
        $can_create_avvisi = true;
    }
@endphp

@if($can_create_avvisi)
    <div id="ctx-avvisi" class="btn-group btn-group-sm ctx-menu" style="display: none;">
        <button type="button" class="btn btn-light createAvvisoCommessa" data-route="{{ route('scadenzario.commessa', $el->id) }}">Crea avviso</button>
    </div>
@endif
