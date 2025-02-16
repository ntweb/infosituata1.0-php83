<div class="main-card mb-3 card" style="min-width: 900px">
    <ul class="list-group list-group-flush commessa-ul">
        <li class="list-group-item">
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">{{ $el->label }}</div>
                    </div>
                    <div class="widget-content-right">
                        @can('commessa_mod_fasi', $el)
                        <button type="button" class="btn btn-primary addNode"
                                data-route="{{ route('commessa-node.create', ['node' => $el->id]) }}">
                            Aggiungi fase
                        </button>
                        @endcan
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

                            @component('dashboard.commesse.components.icons.node-icon', ['node' => $node, 'mr' => 'mr-2'])
                            @endcomponent

                            <div class="widget-heading">{{ $node->label }}</div>
                        </div>
                        <div class="widget-content-right d-flex align-items-center justify-content-end">

                            <span
                                class="badge @if($node->data_inizio_prevista) badge-light @else badge-warning @endif mx-1"
                                data-toggle="tooltip" data-placement="top" data-title="Date inizio e fine previste">
                                @if($node->data_inizio_prevista)
                                    {{ data($node->data_inizio_prevista) }}
                                    -
                                    {{ data($node->data_fine_prevista) }}
                                @else
                                    date non indicate
                                @endif
                            </span>

                            <span class="badge badge-light text-right mx-1" data-toggle="tooltip" data-placement="top" data-title="Costo aziendale previsto" style="min-width: 100px">
                                @if($node->costo_previsto)
                                    {{ euro($node->costo_previsto) }} &euro;
                                @endif
                            </span>

                            {{--                            <span class="badge badge-light mx-1" data-toggle="tooltip" data-placement="top" data-title="Prezzo al cliente">--}}
                            {{--                                @if($node->prezzo_cliente)--}}
                            {{--                                    {{ euro($node->prezzo_cliente) }} &euro;--}}
                            {{--                                @endif--}}
                            {{--                            </span>--}}

                            {{-- Dipendenza --}}
                            @if($node->execute_after_id)
                                <button class="mx-1 btn-pill btn btn-danger btn-sm"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Inizia dopo: {{ $node->executeAfter->label }}"><i
                                        class="bx bx-stopwatch"></i>
                                </button>
                            @else
                                <button class="mx-1 btn-pill btn btn-light btn-sm" disabled>
                                    <i class="bx bx-stopwatch"></i>
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
                                                data-route="{{ route('commessa-node.move', [$node->id, 'up']) }}">
                                            <i class="bx bx-up-arrow-alt"></i>
                                        </button>
                                    @endif

                                    @if (!$loop->last)
                                        <button class="border-0 btn-transition btn btn-outline-link nodeMove"
                                                data-toggle="tooltip" data-placement="top"
                                                data-route="{{ route('commessa-node.move', [$node->id, 'down']) }}">
                                            <i class="bx bx-down-arrow-alt"></i>
                                        </button>
                                    @endif
                                </div>

                                {{-- Menù funzione --}}
                                @can('commessa_mod_fasi', $el)
                                    <div class="dropdown d-inline-block">
                                        <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown" class="btn btn-light btn-sm"><i
                                                class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                             class="dropdown-menu-right dropdown-menu-rounded dropdown-menu"
                                             x-placement="bottom-end"
                                             style="position: absolute; transform: translate3d(-180px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">

                                                <a href="javascript:void(0)" tabindex="0" class="dropdown-item addNode"
                                                   data-route="{{ route('commessa-node.create', ['node' => $node->id]) }}">
                                                    <i class="bx bx-plus mr-2"></i> Crea sottofase
                                                </a>
                                                <div class="dropdown-divider"></div>

                                                @can('commessa_uploads', $node->root)
                                                    <a href="javascript:void(0)" tabindex="0" class="dropdown-item uploadDocNode"
                                                       data-route="{{ route('upload-s3.modal', ['reference_id' => $node->id, 'reference_table' => 'commesse']) }}">
                                                        <i class="bx bx-archive-in mr-2"></i> Upload documenti
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                @endcan

                                                <a href="javascript:void(0)" tabindex="0" class="dropdown-item modNode"
                                                   data-route="{{ route('commessa-node.edit', $node->id) }}">
                                                    <i class="bx bx-edit-alt mr-2"></i> Modifica
                                                </a>
                                                <a href="javascript:void(0)" tabindex="0"
                                                   class="dropdown-item text-danger deleteNode"
                                                   data-route="{{ route('commessa-node.edit', [$node->id, 'delete' => 1]) }}">
                                                    <i class="bx bx-trash-alt mr-2"></i> Elimina
                                                </a>
                                        </div>
                                    </div>
                                @endcan

                            </div>
                        </div>
                    </div>
                </div>
            </li>

            {{-- Sottofasi --}}
            @foreach($node->children as $child)
                @if(!$child->item_id)
                    @php $padding = '40px'; @endphp
                    @include('dashboard.commesse.components.tree-child')

{{--                    @if($child->children)--}}
{{--                        @php--}}
{{--                            $padding = '80px';--}}
{{--                            $childTypes = $child->children->groupBy('type');--}}
{{--                        @endphp--}}

{{--                        @foreach($childTypes as $children)--}}
{{--                            @foreach($children as $child)--}}
{{--                                @include('dashboard.commesse.components.tree-child-item')--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                    @endif--}}

                    @if($child->children)
                        @php
                            $padding = '80px';
                        @endphp

                        @foreach($child->children as $child)
                            @include('dashboard.commesse.components.tree-child-item')
                        @endforeach

                    @endif

                @endif
            @endforeach

            {{-- Utenti Mezzi Attrezzature --}}
            @php
                $padding = '40px';
                 $childTypes = $node->children->groupBy('type');
                 $childTypes = $childTypes->filter(function($el, $key) { return ($key == 'utente' || $key == 'mezzo' || $key == 'attrezzatura' || $key == 'materiale'); })
            @endphp

            @foreach($childTypes as $children)
                @foreach($children as $child)
                @endforeach
            @endforeach
            @foreach($node->children as $child)
                @if($child->item_id)
                    @include('dashboard.commesse.components.tree-child-item')
                @endif
            @endforeach
        @endforeach

    </ul>
</div>
