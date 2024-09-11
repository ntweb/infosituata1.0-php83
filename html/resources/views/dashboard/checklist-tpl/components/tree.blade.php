<div class="main-card mb-3 card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">{{ $el->label }}</div>
                    </div>
                    <div class="widget-content-right">
                        @if(!$el->fl_prod)
                        <button type="button" class="btn btn-primary addNode"
                                data-route="{{ route('checklist-template-node.create', ['node' => $el->id]) }}">
                            Aggiungi sezione
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </li>

        @foreach($tree as $node)
            {{-- Fase  --}}
            <li class="list-group-item py-2 pt-4 bg-heavy-rain">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left d-flex align-items-center">
                            <div class="widget-heading">{{ $node->label }}</div>
                        </div>
                        <div class="widget-content-right d-flex align-items-center">

                            <div class="d-flex justify-content-end" style="min-width: 120px">

                                {{-- Spostamenti --}}
                                <div class="mx-1">
                                    @if (!$loop->first)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('checklist-template-node.move', [$node->id, 'up']) }}">
                                            <i class="bx bx-up-arrow-alt"></i>
                                        </button>
                                    @endif

                                    @if (!$loop->last)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('checklist-template-node.move', [$node->id, 'down']) }}">
                                            <i class="bx bx-down-arrow-alt"></i>
                                        </button>
                                    @endif
                                </div>

                                {{-- Menù funzione --}}
                                <div class="dropdown d-inline-block">
                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                            data-toggle="dropdown" class="btn btn-light btn-sm"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="dropdown-menu-right dropdown-menu-rounded dropdown-menu"
                                         x-placement="bottom-end"
                                         style="position: absolute; transform: translate3d(-180px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        @if(!$el->fl_prod)
                                            <h6 class="dropdown-header">Aggiungi elemento</h6>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'input']) }}">
                                                <i class="bx bx-edit-alt mr-2"></i> Input
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'textarea']) }}">
                                                <i class="bx bx-edit mr-2"></i> Textarea
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'date']) }}">
                                                <i class="bx bx-calendar-edit mr-2"></i> Data
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'select']) }}">
                                                <i class="bx bx-objects-horizontal-left mr-2"></i> Select
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'radio']) }}">
                                                <i class="bx bx-radio-circle-marked mr-2"></i> Radio
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('checklist-template-node.create', ['node' => $node->id, '_module' => 'checkbox']) }}">
                                                <i class="bx bx-checkbox-checked mr-2"></i> Checkbox
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('checklist-template-node.edit', $node->id) }}">
                                            <i class="bx bx-edit-alt mr-2"></i> Modifica
                                        </a>

                                        @if(!$el->fl_prod)
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('checklist-template-node.edit', [$node->id, 'delete' => 1]) }}">
                                            <i class="bx bx-trash-alt mr-2"></i> Elimina
                                        </a>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </li>

            {{-- Sottofasi --}}
            @foreach($node->children as $child)
                <li class="list-group-item no-border-top py-1 bg-light">
                    <div class="widget-content p-0 pl-lg-4">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left d-flex align-items-center">
                                @component('dashboard.checklist-tpl.components.node-icon', ['el' => $child])
                                @endcomponent
                                <div class="widget-heading font-weight-normal">{{ $child->label }} @if($child->required) <em><small class="font-weight-bold text-danger">(richiesto)</small></em> @endif</div>
                            </div>
                            <div class="widget-content-right d-flex align-items-center">

                                <div class="d-flex justify-content-end" style="min-width: 120px">
                                    {{-- Spostamenti --}}
                                    <div class="mx-1">
                                        @if (!$loop->first)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('checklist-template-node.move', [$child->id, 'up']) }}">
                                                <i class="bx bx-up-arrow-alt"></i>
                                            </button>
                                        @endif

                                        @if (!$loop->last)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('checklist-template-node.move', [$child->id, 'down']) }}">
                                                <i class="bx bx-down-arrow-alt"></i>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Menù funzione --}}
                                    @if(!$el->fl_prod)
                                    <div class="dropdown d-inline-block">
                                        <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown" class="btn btn-light btn-sm"><i
                                                class="bx bx-dots-vertical-rounded"></i></button>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                             class="dropdown-menu-right dropdown-menu-rounded dropdown-menu"
                                             x-placement="bottom-end"
                                             style="position: absolute; transform: translate3d(-180px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('checklist-template-node.edit', $child->id) }}">
                                                <i class="bx bx-edit-alt mr-2"></i> Modifica
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('checklist-template-node.edit', [$child->id, 'delete' => 1]) }}">
                                                <i class="bx bx-trash-alt mr-2"></i> Elimina
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        @endforeach

    </ul>
</div>
