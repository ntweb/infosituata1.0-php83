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
                                data-route="{{ route('commessa-template-node.create', ['node' => $el->id]) }}">
                            Aggiungi fase
                        </button>
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
                            <i class="bx bxs-circle mr-2" style="color: {{ $node->color ?? '#fefefe' }}"></i>
                            <div class="widget-heading">{{ $node->label }}</div>
                        </div>
                        <div class="widget-content-right d-flex align-items-center">

                            {{-- Dipendenza --}}
                            @if($node->execute_after_id)
                                <button class="mx-1 btn-pill btn btn-danger btn-sm"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Inizia dopo: {{ $node->executeAfter->label }}"><i class="bx bx-stopwatch"></i>
                                </button>
                            @endif

{{--                            @if($node->time === 'd')--}}
{{--                                <button class="mx-1 btn-pill btn btn-dashed btn-outline-dark btn-sm"--}}
{{--                                        data-toggle="tooltip" data-placement="top"--}}
{{--                                        data-original-title="Conteggio giornaliero"><i class="bx bx-calendar-event"></i>--}}
{{--                                </button>--}}
{{--                            @endif--}}

{{--                            @if($node->time === 'h')--}}
{{--                                <button class="mx-1 btn-pill btn btn-dashed btn-outline-info btn-sm"--}}
{{--                                        data-toggle="tooltip" data-placement="top"--}}
{{--                                        data-original-title="Conteggio orario"><i class="bx bxs-hourglass"></i>--}}
{{--                                </button>--}}
{{--                            @endif--}}

                            <div class="d-flex justify-content-end" style="min-width: 120px">

                                {{-- Spostamenti --}}
                                <div class="mx-1">
                                    @if (!$loop->first)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('commessa-template-node.move', [$node->id, 'up']) }}">
                                            <i class="bx bx-up-arrow-alt"></i>
                                        </button>
                                    @endif

                                    @if (!$loop->last)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('commessa-template-node.move', [$node->id, 'down']) }}">
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
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode" data-route="{{ route('commessa-template-node.create', ['node' => $node->id]) }}">
                                            <i class="bx bx-plus mr-2"></i> Crea sottofase
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('commessa-template-node.edit', $node->id) }}">
                                            <i class="bx bx-edit-alt mr-2"></i> Modifica
                                        </a>
                                        <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('commessa-template-node.edit', [$node->id, 'delete' => 1]) }}">
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

                                {{-- Dipendenza --}}
                                @if($child->execute_after_id)
                                    <button class="mx-1 btn-pill btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="Inizia dopo: {{ $child->executeAfter->label }}"><i class="bx bx-stopwatch"></i>
                                    </button>
                                @endif

{{--                                @if($child->time === 'd')--}}
{{--                                    <button class="mx-1 btn-pill btn btn-dashed btn-outline-dark btn-sm"--}}
{{--                                            data-toggle="tooltip" data-placement="top"--}}
{{--                                            data-original-title="Conteggio giornaliero"><i class="bx bx-calendar-event"></i>--}}
{{--                                    </button>--}}
{{--                                @endif--}}

{{--                                @if($child->time === 'h')--}}
{{--                                    <button class="mx-1 btn-pill btn btn-dashed btn-outline-info btn-sm"--}}
{{--                                            data-toggle="tooltip" data-placement="top"--}}
{{--                                            data-original-title="Conteggio orario"><i class="bx bxs-hourglass"></i>--}}
{{--                                    </button>--}}
{{--                                @endif--}}

                                <div class="d-flex justify-content-end" style="min-width: 120px">
                                    {{-- Spostamenti --}}
                                    <div class="mx-1">
                                        @if (!$loop->first)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('commessa-template-node.move', [$child->id, 'up']) }}">
                                                <i class="bx bx-up-arrow-alt"></i>
                                            </button>
                                        @endif

                                        @if (!$loop->last)
                                            <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-route="{{ route('commessa-template-node.move', [$child->id, 'down']) }}">
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
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode" data-route="{{ route('commessa-template-node.edit', $child->id) }}">
                                                <i class="bx bx-edit-alt mr-2"></i> Modifica
                                            </a>
                                            <a href="javascript:void(0)" tabindex="0" class="dropdown-item text-danger deleteNode" data-route="{{ route('commessa-template-node.edit', [$child->id, 'delete' => 1]) }}">
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
