<form action="#" class="ns-html" data-route-placeholder="{{ route('checklist.render', '#') }}" data-container="#modal-ajax-html" data-callback="$('#template-selection').remove(); openModalGeneratedChecklist();">

    <input type="hidden" name="reference_controller" value="{{ $reference_controller }}">
    @if($items_id)
        <input type="hidden" name="items_id" value="{{ $items_id }}">
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                Seleziona il template
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                @if(count($checklists))
                    @component('layouts.components.forms.select', ['name' => 'checklists_templates_id', 'value' => null, 'class' => 'col-md-12', 'elements' => $checklists])
                        Checklist
                    @endcomponent
                @else
                    <div class="w-100">
                        @component('layouts.components.alerts.warning')
                            Nessuna checklist trovata
                        @endcomponent
                    </div>
                @endif
            </div>
        </div>
        <div class="d-block text-right card-footer">
            <a class="btn btn-light btn-lg" href="{{ route('checklist.create') }}"><i class="bx bx-chevron-left"></i>
                Indietro</a>
            <button class="btn btn-primary btn-lg" type="button" id="btnSelectChecklistTempleteRender">Crea</button>
        </div>
    </div>
</form>
