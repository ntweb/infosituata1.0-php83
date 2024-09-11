@php
    switch ($item->controller) {
        case 'utente':
            $title = strtoupper($item->extras1.' '.$item->extras2);
            break;

        case 'mezzo':
        case 'attrezzatura':
        case 'risorsa':
            $title = strtoupper($item->extras1);
            break;

        default:
            $title = 'nd';
    }

@endphp

<div class="row" id="scadenzario-moduli">

    @foreach($moduli_details as $md)
    <div class="col-md-3">
        <div class="card-hover-shadow-2x mb-3 card">
            <div class="card-header">{{ Str::title($md->label) }}</div>
            <div class="d-block text-right card-footer">
                <button class="btn-shadow-primary btn btn-primary btn-lg btnOpenModulo btn-block" data-v="#scadenzario-modulo-{{ $md->id }}">Crea scadenza</button>
            </div>
        </div>
    </div>
    @endforeach

</div>


<div class="row">
    <div class="col-md-6">
        @foreach($moduli_details as $md)
            <form class="ns scadenzario-modulo-details" id="scadenzario-modulo-{{ $md->id }}" action="{{ route('scadenzario.store', ['_type' => 'json']) }}" autocomplete="none" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="infosituata_moduli_details_id" value="{{ $md->id }}">

                <div class="mb-3 card main-card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                            {{ $title }} / {{ Str::title($md->label) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">

                            @component('layouts.components.forms.details-scadenze', ['name' => 'infosituata_moduli_details_scadenze_id', 'id' => 'infosituata_moduli_details_scadenze_id_'.$md->id, 'value' => @$el->infosituata_moduli_details_scadenze_id, 'infosituata_moduli_details_id' => $md->id, 'azienda_id' => $item->azienda_id, 'route' => route('scadenzario.store-new-tipologia-scadenza', [$md->id, $item->azienda_id, '_type' => 'json']), 'is_checked' => false, 'class' => 'col-md-12'])
                                Tipologia scadenza
                            @endcomponent

                            @component('layouts.components.forms.date-picker', ['name' => 'start_at', 'id' => 'start_at_'.$md->id, 'value' => @$el->started_at ? data($el->started_at) : date('d/m/Y'), 'class' => 'col-md-6 detailScadenzeStartAt'])
                                Data di partenza
                            @endcomponent

                            @component('layouts.components.forms.date-picker', ['name' => 'end_at', 'id' => 'end_at_'.$md->id, 'value' => @$el->end_at ? data($el->end_at) : null, 'class' => 'col-md-6'])
                                Data di scadenza
                            @endcomponent
                        </div>
                    </div>
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-speaker icon-gradient bg-love-kiss"> </i>
                            Avvisi
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            @component('layouts.components.forms.number-group', ['name' => 'avvisa_entro_gg', 'id' => 'avvisa_entro-'.$md->id, 'value' => @$el->avvisa_entro_gg ? $el->avvisa_entro_gg : 0, 'min' => 0, 'step' => 1, 'group_text' => 'giorni', 'group_align' => 'right', 'class' => 'col-md-4'])
                                Avvisa entro
                            @endcomponent

                            @if($item->controller == 'utente')
                                @component('layouts.components.forms.select', ['name' => 'advice_item', 'id' => 'avvisa_item-'.$md->id, 'value' => @$el->advice_item, 'elements' => ['0' => 'no', '1' => 'si'], 'inline' => true, 'class' => 'col-md-8'])
                                    Inviare l'avviso all'utente?
                                @endcomponent
                            @endif

                            @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi', 'id' => 'gruppi-'.$md->id, 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel])
                                Avvisa i seguenti gruppi broadcast
                            @endcomponent
                        </div>
                    </div>
                    <div class="d-block text-right card-footer">
                        <button type="button" class="mr-2 btn btn-link btn-sm btnCloseScadenzarioModulo">Annulla</button>
                        <button type="submit" class="btn btn-primary btn-lg">Salva</button>
                    </div>
                </div>
            </form>
        @endforeach
    </div>
</div>
