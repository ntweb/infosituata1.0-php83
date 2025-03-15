@php
    $extraFields = getExtraFieldsStructure($el);
    $extraNode = json_decode($node->extra_fields, true);
    $can_mod_status = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_stato', $el)) {
        $can_mod_status = true;
    }

    $can_view_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_view_costi', $el)) {
        $can_view_costi = true;
    }

    $can_create_risorsa = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_create', $el)) {
        $can_create_risorsa = true;
    }

    $can_modify_fasi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_mod_fasi', $el)) {
        $can_modify_fasi = true;
    }

    $can_create_risorse = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_create', $el)) {
        $can_create_risorse = true;
    }

    $can_view_commessa_log = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_view_log', $el)) {
        $can_view_commessa_log = true;
    }

    $can_view_risorse_log = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_view_log', $el)) {
        $can_view_risorse_log = true;
    }

    $can_create_risorse_log = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_create_log', $el)) {
        $can_create_risorse_log = true;
        $can_view_risorse_log = true;
    }

@endphp

<tr id="node-{{ $node->id }}" class="tr-node-selector" data-depend-on="node-{{ $node->execute_after_id }}" data-node-toggle="{{ $node->type }}">
    <td class="fit" @if($node->depth > 1) style="padding-left: {{ 20 * $node->depth }}px" @endif>
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="btn btn-outline-link py-0 hightlight-tr-dependant" data-id="node-{{ $node->id }}">
            @component('dashboard.commesse.components.icons.node-icon', ['node' => $node])
            @endcomponent
            {{ $node->label }}
        </button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -4px, 0px);">

            @if(!$node->item_id && $node->type != 'extra')

                @if($can_create_risorsa)
                    {{-- Assegnazioni --}}
                    <h6 class="dropdown-header">Assegna</h6>
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'utente']) }}">
                        <i class="bx bx-user mr-2"></i> Utente
                    </a>
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'mezzo']) }}">
                        <i class="bx bxs-car-mechanic mr-2"></i> Mezzo
                    </a>
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'attrezzatura']) }}">
                        <i class="bx bx-wrench mr-2"></i> Attrezzatura
                    </a>
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'materiale']) }}">
                        <i class="bx bx-package mr-2"></i> Materiale
                    </a>
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'squadra']) }}">
                        <i class="bx bxs-user-plus mr-2"></i> Squadra
                    </a>
                    @can('testing')
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                       data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()', '_module' => 'extra']) }}">
                        <i class="bx bx-extension mr-2"></i> Extra
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                @endif


                @can('commessa_mod_fasi', $el)
                    @if($node->type == 'fase_lv_1')
                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                           data-route="{{ route('commessa-node.create', ['node' => $node->id, '_callback' => 'refreshOverviewTable()']) }}">
                            <i class="bx bx-plus mr-2"></i> Crea sottofase
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                @endcan

                @can('commessa_uploads', $el)
                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item uploadDocNode"
                       data-route="{{ route('upload-s3.modal', ['reference_id' => $node->id, 'reference_table' => 'commesse']) }}">
                        <i class="bx bx-archive-in mr-2"></i> Upload documenti
                    </a>
                    <div class="dropdown-divider"></div>
                @endcan

            @endif

            {{-- fasi --}}
            @if($can_modify_fasi && !$node->item_id)
                @if($node->type != 'extra')
                <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode"
                   data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                    <i class="bx bx-edit-alt mr-2"></i> Modifica
                </a>
                @endif
                <a href="javascript:void(0)" tabindex="0"
                   class="dropdown-item text-danger deleteNode"
                   data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()', 'delete' => 1]) }}">
                    <i class="bx bx-trash-alt mr-2"></i> Elimina
                </a>
                <div tabindex="-1" class="dropdown-divider"></div>
            @endif

            {{-- risorse --}}
            @if($can_create_risorse && $node->item_id)
                <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode"
                   data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                    <i class="bx bx-edit-alt mr-2"></i> Modifica
                </a>
                <a href="javascript:void(0)" tabindex="0"
                   class="dropdown-item text-danger deleteNode"
                   data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()', 'delete' => 1]) }}">
                    <i class="bx bx-trash-alt mr-2"></i> Elimina
                </a>
            @endif

            @if($can_view_risorse_log && $node->item_id)
                <div tabindex="-1" class="dropdown-divider"></div>
                <button type="button" tabindex="0" class="dropdown-item openNodeLog" data-route="{{ route('commessa-node.logs', $node->id) }}">Log</button>
            @endif

        </div>
    </td>
    <td>
        @if($node->note)
            <a href="javascript:void(0)" class="openModalNote" data-route="{{ route('commessa-node.note', $node->id) }}">
                <i class="bx bxs-note text-warning"></i>
            </a>
        @endif
    </td>
    <td class="fit">
        @if($node->execute_after_id)
            <a href="javascript:void(0)" class="hightlight-tr" data-hightlight="node-{{ $node->execute_after_id }}" style="text-decoration: none">
                @component('dashboard.commesse.components.icons.node-icon', ['node' => $node->executeAfter])
                @endcomponent

                {{ $node->executeAfter->label }}
            </a>
        @endif
    </td>
{{--    <td class="text-center">--}}
{{--        @component('dashboard.commesse.components.labels.node-label-conteggio', ['node' => $node])--}}
{{--        @endcomponent--}}
{{--    </td>--}}
    <td class="fit">
        <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
           data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
            @component('dashboard.commesse.components.labels.node-label-date-prev', ['node' => $node])
            @endcomponent
        </a>
    </td>
    <td class="fit">
        @if($node->item_id || $node->type == 'extra')
            @if($can_view_risorse_log)
            <a href="javascript:void(0)" id="openNodeLog-{{ $node->id }}" class="openNodeLog" style="text-decoration: none;" data-route="{{ route('commessa-node.logs', $node->id) }}">
                @component('dashboard.commesse.components.labels.node-label-date-eff', ['node' => $node])
                @endcomponent
            </a>
            @endif
        @else
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-date-eff', ['node' => $node])
                @endcomponent
            </a>
        @endif
    </td>
    @if($can_view_costi)
        <td class="fit text-right" data-node-toggle="costi">
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-prezzo', ['node' => $node, 'field' => 'costo_previsto'])
                @endcomponent
            </a>
        </td>
        <td class="fit text-right" data-node-toggle="costi">
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-prezzo', ['node' => $node, 'field' => 'costo_effettivo'])
                @endcomponent
            </a>
        </td>
        <td class="fit text-right" data-node-toggle="costi">
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-prezzo', ['node' => $node, 'field' => 'prezzo_cliente'])
                @endcomponent
            </a>
        </td>
        <td class="fit text-right" data-node-toggle="costi">
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-ricavo-prev', ['node' => $node])
                @endcomponent
            </a>
        </td>
        <td class="fit text-right" data-node-toggle="costi">
            <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
               data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
                @component('dashboard.commesse.components.labels.node-label-ricavo-cons', ['node' => $node])
                @endcomponent
            </a>
        </td>
    @endif
    @foreach($extraFields as $ef)
        <td class="fit text-right" data-node-toggle="extras">
            @can('commessa_update_extra_fields', $el)
                <select class="selectExtraField"
                        name="extra[{{ $ef->label }}]"
                        data-node-extra-field="{{ $node->id }}"
                        data-route="{{ route('commessa-node.extra', [$node->id]) }}">
                    <option value=""></option>
                    @foreach($ef->v as $index => $v)
                        @if($v)
                            <option value="{{ $v }}" @if(@$extraNode[$ef->label] == $v) selected @endif data-color="{{ $ef->c->$index }}">{{ $v }}</option>
                        @endif
                    @endforeach
                </select>
            @else
                <select class="selectExtraField"
                        name="extra[{{ $ef->label }}]"
                        data-node-extra-field="{{ $node->id }}"
                        data-route=""
                        disabled>
                    <option value=""></option>
                    @foreach($ef->v as $index => $v)
                        @if($v)
                            <option value="{{ $v }}" @if(@$extraNode[$ef->label] == $v) selected @endif data-color="{{ $ef->c->$index }}">{{ $v }}</option>
                        @endif
                    @endforeach
                </select>
            @endcan
        </td>
    @endforeach
    <td class="fit text-right">
        <a href="javascript:void(0)" class="modNode" style="text-decoration: none;"
           data-route="{{ route('commessa-node.edit', [$node->id, '_callback' => 'refreshOverviewTable()']) }}">
            @component('dashboard.commesse.components.labels.node-label-ritardo', ['node' => $node])
            @endcomponent
        </a>
    </td>
    <td class="fit text-right">
        @if($node->fl_is_status_changeble && $can_mod_status)
            <a href="javascript:void(0)"
               class="nodeChangeStatus"
               data-route="{{ route('commessa-node.status-change', [$node->id]) }}"
               data-toggle="tooltip"
               data-placement="top"
               data-title="cambia stato">
                @component('dashboard.commesse.components.labels.node-label-stato', ['node' => $node])
                @endcomponent
            </a>
        @else
            @component('dashboard.commesse.components.labels.node-label-stato', ['node' => $node])
            @endcomponent
        @endif
    </td>
</tr>
@foreach($node->children as $child)
    @component('dashboard.commesse.analisi.components.overview-table-row', ['el' => $el, 'node' => $child])
    @endcomponent
@endforeach
