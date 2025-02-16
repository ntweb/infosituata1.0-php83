<div class="row">
    <div class="col-md-12" id="div-list-assegnati">

        @if(count($tasks))
            <div class="main-card mb-3 card pointer pointer-click" data-route="{{ route('task.assegnati') }}">
                    <ul class="list-group list-group-flush task-ul">

                        @foreach($tasks as $node)
                            {{-- Fase  --}}
                            <li class="list-group-item py-2 pt-4" @if($node->started_at && $node->completed_at) style="background: #bde5bd" @endif @if($node->started_at && !$node->completed_at) style="background: #f9ddcc" @endif>
                                <div class="widget-content p-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex flex-column">
                                            @if($node->root->cliente)
                                                <div class="d-flex flex-column align-items-end">
                                                    <div class="h4">Cliente: {{ $node->root->cliente->rs }}</div>
                                                </div>
                                            @endif
                                            <div class="d-flex flex-column align-items-start mb-2">
                                                <div class="h4">Task: {{ $node->label }}</div>
                                                @if($node->users_ids)
                                                    @php
                                                        $users = getUsersFromIds(json_decode($node->users_ids));
                                                    @endphp
                                                    <div>
                                                        <strong>Assegnato a:</strong> <span class="font-italic">{{ $users->pluck('name')->join(', ') }}</span>
                                                    </div>
                                                @else
                                                    <span class="font-italic text-danger">Task non assegnato</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">

                                            <div class="d-flex flex-column">

                                                <div class="d-flex mb-2">
                                                    @if($node->data_inizio_prevista || $node->data_fine_prevista)
                                                        <div class="d-flex flex-column align-items-start" style="min-width: 120px">
                                                            <small>Inizio previsto</small>
                                                            <span class="badge badge-light">
                                                        {{ $node->data_inizio_prevista ? dataOra($node->data_inizio_prevista) : '-' }}
                                                    </span>
                                                        </div>

                                                        <div class="d-flex flex-column  align-items-start" style="min-width: 120px">
                                                            <small>Fine prevista</small>
                                                            <span class="badge badge-light">
                                                        {{ $node->data_fine_prevista ? dataOra($node->data_fine_prevista) : '-' }}
                                                    </span>
                                                        </div>
                                                    @endif

                                                    @if(!$node->data_inizio_prevista && !$node->data_fine_prevista)
                                                        <div class="d-flex flex-column  align-items-start" style="min-width: 240px">
                                                            <small>Date previsionali</small>
                                                            <span class="badge badge-light text-warning">
                                                        non indicate
                                                    </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="d-flex">
                                                    @if($node->started_at || $node->completed_at)
                                                        <div class="d-flex flex-column align-items-start" style="min-width: 120px">
                                                            <small>Inizio</small>
                                                            <span class="badge badge-light">
                                                            {{ $node->started_at ? dataOra($node->started_at) : '-' }}
                                                        </span>
                                                        </div>

                                                        <div class="d-flex flex-column  align-items-start" style="min-width: 120px">
                                                            <small>Fine</small>
                                                            <span class="badge badge-light">
                                                            {{ $node->completed_at ? dataOra($node->completed_at) : '-' }}
                                                        </span>
                                                        </div>
                                                    @endif

                                                    @if(!$node->started_at && !$node->completed_at)
                                                        <div class="d-flex flex-column  align-items-start" style="min-width: 240px">
                                                            <small>Date lavorazione</small>
                                                            <span class="badge badge-light text-warning">
                                                            non indicate
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </a>
            </div>
        @else
            @component('layouts.components.alerts.warning')
                Nessun elemento trovato
            @endcomponent
        @endif
    </div>
</div>

