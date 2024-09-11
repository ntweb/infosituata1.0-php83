@php
    $can_view_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_view_costi', $el)) {
        $can_view_costi = true;
    }
@endphp

@if($can_view_costi)
    <div class="d-flex align-items-center flex-wrap">
        <div class="ml-4 my-2 bg-white p-2 border rounded d-flex flex-column shadow align-items-start flex-even" style="min-width: 150px">
            <small class="font-weight-bold">Costo azi. prev.</small>
            <p class="mb-0">{{ euro($el->costo_previsto) }} &euro;</p>
        </div>
        <div class="ml-4 my-2 bg-white p-2 border rounded d-flex flex-column shadow align-items-start flex-even" style="min-width: 150px">
            <small class="font-weight-bold">Costo azi. cons.</small>
            <p class="mb-0">{{ euro($el->costo_effettivo) }} &euro;</p>
        </div>
        <div class="ml-4 my-2 bg-white p-2 border rounded d-flex flex-column shadow align-items-start flex-even" style="min-width: 150px">
            <small class="font-weight-bold">Riv cli.</small>
            <p class="mb-0">{{ euro($el->prezzo_cliente) }} &euro;</p>
        </div>
        <div class="ml-4 my-2 bg-white p-2 border rounded d-flex flex-column shadow align-items-start flex-even" style="min-width: 150px">
            <small class="font-weight-bold">Ric. prev.</small>
            <p class="mb-0">{{ euro($el->prezzo_cliente - $el->costo_previsto) }} &euro;</p>
        </div>
        <div class="ml-4 my-2 bg-white p-2 border rounded d-flex flex-column shadow align-items-start flex-even" style="min-width: 150px">
            <small class="font-weight-bold">Ric. cons.</small>
            <p class="mb-0">{{ euro($el->prezzo_cliente - $el->costo_effettivo) }} &euro;</p>
        </div>
    </div>
@endif
