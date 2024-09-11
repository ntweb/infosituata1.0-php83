<div class="modal fade" id="modalChecklist" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuova checklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="checklist-create-body">
                <div class="row">

                    @component('layouts.components.forms.select', ['name' => 'checklists_templates_id', 'value' => null, 'class' => 'col-md-12', 'elements' => $checklists])
                        Checklist
                    @endcomponent

                    @component('layouts.components.forms.select2-fasi-commessa', ['name' => 'commesse_checklist_id', 'value' => null, 'class' => 'col-md-12', 'commesse_id' => $el->id])
                        Associa a fase / sottofase di riferimento
                    @endcomponent
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary createChecklistCommessaDo" data-new="0" data-route="{{ route('checklist.render', 'xxx') }}">Crea</button>
            </div>
        </div>
    </div>
</div>
