@php
    $can_create_checklist = false;
    if (\Illuminate\Support\Facades\Gate::allows('checklist_create', $el)) {
        $can_create_checklist = true;
    }
@endphp

@if($can_create_checklist)
    <div id="ctx-checklist" class="btn-group btn-group-sm ctx-menu" style="display: none;">
        <button type="button" class="btn btn-light createChecklistCommessa" data-route="{{ route('checklist.commessa', $el->id) }}">Crea checklist</button>
    </div>
@endif
