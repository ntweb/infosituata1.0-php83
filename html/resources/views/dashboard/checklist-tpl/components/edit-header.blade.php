<div class="dropdown d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Menù</button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
        <a href="{{ route('checklist-template.duplicate', $el->id) }}" tabindex="0" class="dropdown-item"><i class="bx bx-duplicate mr-2"></i> Duplica template</a>
        <div class="dropdown-divider"></div>
        <a href="javascript:void(0);" data-route="{{ route('checklist.render', [$el->id, '_demo' => 1]) }}" tabindex="0" class="dropdown-item renderChecklist"><i class="bx bx-spreadsheet mr-2"></i> Render checklist</a>
    </div>
</div>
