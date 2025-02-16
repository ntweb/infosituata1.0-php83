<div class="main-card mb-3 card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">{{ $el->label }}</div>
                    </div>
                    <div class="widget-content-right">
                        <button type="button" class="btn btn-primary addNode"
                                data-route="{{ route('task-template-node.create', ['node' => $el->id]) }}">
                            Aggiungi task
                        </button>
                    </div>
                </div>
            </div>
        </li>

        @foreach($tree as $node)
            {{-- Fase  --}}
            <li class="list-group-item py-2 pt-4">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left d-flex flex-column align-items-start justify-content-center">
                            <div class="widget-heading font-weight-normal">{{ $node->label }}</div>
                            <div><small>{{ $node->description }}</small></div>
                        </div>
                        <div class="widget-content-right d-flex align-items-center">

                            <div class="d-flex justify-content-end" style="min-width: 120px">

                                {{-- Spostamenti --}}
                                <div class="mx-1">
                                    @if (!$loop->first)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('task-template-node.move', [$node->id, 'up']) }}">
                                            <i class="bx bx-up-arrow-alt"></i>
                                        </button>
                                    @endif

                                    @if (!$loop->last)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('task-template-node.move', [$node->id, 'down']) }}">
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
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('task-template-node.edit', $node->id) }}">
                                            <i class="bx bx-edit-alt mr-2"></i> Modifica
                                        </a>
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('task-template-node.edit', [$node->id, 'delete' => 1]) }}">
                                            <i class="bx bx-trash-alt mr-2"></i> Elimina
                                        </a>
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
                                <i class="bx bxs-circle mr-2" style="color: {{ $child->color ?? '#fefefe' }}"></i>
                                <div class="widget-heading">{{ $child->label }}</div>
                            </div>
                            <div class="widget-content-right d-flex align-items-center">

                                <div class="d-flex justify-content-end" style="min-width: 120px">
                                    {{-- Spostamenti --}}
                                    <div class="mx-1">
                                        @if (!$loop->first)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('task-template-node.move', [$child->id, 'up']) }}">
                                                <i class="bx bx-up-arrow-alt"></i>
                                            </button>
                                        @endif

                                        @if (!$loop->last)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('task-template-node.move', [$child->id, 'down']) }}">
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
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('task-template-node.edit', $child->id) }}">
                                                <i class="bx bx-edit-alt mr-2"></i> Modifica
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('task-template-node.edit', [$child->id, 'delete' => 1]) }}">
                                                <i class="bx bx-trash-alt mr-2"></i> Elimina
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        @endforeach

    </ul>
</div>
