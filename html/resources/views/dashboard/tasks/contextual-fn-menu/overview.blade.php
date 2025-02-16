@php
    $can_create_rapportini = false;
    if (\Illuminate\Support\Facades\Gate::allows('rapportini_create', $el)) {
        $can_create_rapportini = true;
    }

    $can_create_checklist = false;
    if (\Illuminate\Support\Facades\Gate::allows('checklist_create', $el)) {
        $can_create_checklist = true;
    }

    $can_update_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_costi', $el)) {
        $can_update_costi = true;
    }

    $can_modify_fasi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_fasi', $el)) {
        $can_modify_fasi = true;
    }

    $can_print = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_print', $el)) {
        $can_print = true;
    }
@endphp
<div id="ctx-overview" class="btn-group btn-group-sm ctx-menu" style="display: none;">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-light">Funzioni</button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(58px, 33px, 0px);">
        <h6 tabindex="-1" class="dropdown-header">Visualizza / nascondi</h6>
        <button type="button" tabindex="0" class="dropdown-item toggleNode toggleUtenti" data-node="utente">Utenti</button>
        <button type="button" tabindex="0" class="dropdown-item toggleNode toggleUtenti" data-node="mezzo">Mezzi</button>
        <button type="button" tabindex="0" class="dropdown-item toggleNode toggleUtenti" data-node="attrezzatura">Attrezzature</button>
        <button type="button" tabindex="0" class="dropdown-item toggleNode toggleUtenti" data-node="materiale">Materiali</button>
        <button type="button" tabindex="0" class="dropdown-item toggleColumn" data-node="costi">Colonne costi</button>
        <button type="button" tabindex="0" class="dropdown-item toggleColumn" data-node="extras">Colonne extras</button>
        @if($can_create_rapportini)
            <div tabindex="-1" class="dropdown-divider"></div>
            <button type="button" tabindex="0" class="dropdown-item" data-toggle="modal" data-target="#rapportinoModal">Crea rapportino</button>
        @endif
        @if($can_create_checklist)
            <div tabindex="-1" class="dropdown-divider"></div>
            <button type="button" tabindex="0" class="dropdown-item createChecklistCommessa" data-route="{{ route('checklist.commessa', $el->id) }}">Crea checklist</button>
        @endif
        <div tabindex="-1" class="dropdown-divider"></div>
        @if($can_update_costi)
            <button type="button" tabindex="0" class="dropdown-item" data-toggle="modal" data-target="#associaCostiModal">Associa costi consuntivi</button>
        @endif
        @if($can_print)
            <button type="button" tabindex="0" class="dropdown-item" data-toggle="modal" data-target="#printModal">Stampa commessa</button>
        @endif
    </div>
</div>
