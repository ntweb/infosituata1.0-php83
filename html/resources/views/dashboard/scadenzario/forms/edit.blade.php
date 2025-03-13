@php
    $title = strtoupper($item->label);
    $_read_only = Gate::allows('can-create') ? false : true;
@endphp


<div class="row">
    <div class="col-md-6">

        <form class="ns" action="{{ route('scadenzario.update', [$scadenza->id, '_type' => 'json']) }}" autocomplete="none" method="post">
                @csrf
                @method('PUT')

                <div class="mb-3 card main-card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                            {{ $title }} / {{ Str::title($scadenza->module->label) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">

                            @component('layouts.components.forms.details-scadenze', ['name' => 'infosituata_moduli_details_scadenze_id', 'value' => @$scadenza->infosituata_moduli_details_scadenze_id, 'infosituata_moduli_details_id' => $scadenza->infosituata_moduli_details_id, 'azienda_id' => $item->azienda_id, 'route' => route('scadenzario.store-new-tipologia-scadenza', [$scadenza->infosituata_moduli_details_id, $scadenza->azienda_id, '_type' => 'json']), 'is_checked' => @$scadenza->checked_at,'class' => 'col-md-12', 'chek_permission' => true])
                                Tipologia scadenza
                            @endcomponent

                            @component('layouts.components.forms.date-picker', ['name' => 'start_at', 'value' => @$scadenza->start_at ? data($scadenza->start_at) : date('d/m/Y'), 'class' => 'col-md-6 detailScadenzeStartAt', 'chek_permission' => true])
                                Data di partenza
                            @endcomponent

                            @component('layouts.components.forms.date-picker', ['name' => 'end_at', 'value' => @$scadenza->end_at ? data($scadenza->end_at) : null, 'class' => 'col-md-6', 'chek_permission' => true])
                                Data di scadenza
                            @endcomponent

                            @component('layouts.components.forms.textarea', ['name' => 'description', 'value' => @$scadenza->description, 'class' => 'col-md-12'])
                                Note
                            @endcomponent
                        </div>
                    </div>
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-speaker icon-gradient bg-love-kiss"> </i>
                            Avvis
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            @component('layouts.components.forms.number-group', ['name' => 'avvisa_entro_gg', 'value' => @$scadenza->avvisa_entro_gg ? $scadenza->avvisa_entro_gg : 0, 'min' => 0, 'step' => 1, 'group_text' => 'giorni', 'group_align' => 'right', 'class' => 'col-md-4', 'chek_permission' => true])
                                Avvisa entro
                            @endcomponent

                            @if($item->controller == 'utente')
                                @component('layouts.components.forms.select', ['name' => 'advice_item', 'value' => @$scadenza->advice_item, 'elements' => ['0' => 'no', '1' => 'si'], 'inline' => true, 'class' => 'col-md-8', 'chek_permission' => true])
                                    Inviare l'avviso all'utente?
                                @endcomponent
                            @endif

                            @component('layouts.components.forms.select2-multiple', ['name' => 'gruppi', 'value' => 'vediamo', 'class' => 'col-md-12', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel, 'chek_permission' => true])
                                Avvisa i seguenti gruppi broadcast
                            @endcomponent
                        </div>
                    </div>
                    @if (!$scadenza->checked_at && !$_read_only)
                    <div class="d-block text-right card-footer">
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalScadenzaCheckControllata">Segna come controllata <i class="fa fa-check"></i></button>
                        <button type="submit" class="btn btn-primary btn-lg">Salva</button>
                    </div>
                    @endif
                </div>
            </form>

        @can('can-create')
            @include('layouts.components.helpers.delete-scadenza')
        @endcan
    </div>
    <div class="col-md-6">

        @component('dashboard.upload.s3.upload', ['reference_id' => $scadenza->id, 'reference_table' => 'scadenze'])
            Scadenza
        @endcomponent

    </div>
</div>

@section('modal')
    @include('dashboard.scadenzario.components.modal-scadenza-checkcontrollata')
    @include('dashboard.scadenzario.components.modal-delete-attachment')
    @include('dashboard.infosituata-moduli.components.modal-scadenza-create')
@endsection
