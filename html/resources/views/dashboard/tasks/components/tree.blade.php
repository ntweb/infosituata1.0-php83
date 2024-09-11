
    <ul class="list-group list-group-flush task-ul">
        <li class="list-group-item">
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    @can('task_mod_fasi', $el)
                    <div class="widget-content-left">
                        <button type="button" class="btn btn-primary addNode"
                                data-route="{{ route('task-node.create', ['node' => $el->id]) }}">
                            Aggiungi task
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </li>

        @foreach($tree as $node)
            {{-- Fase  --}}
            <li class="list-group-item py-2 pt-4">
                <div class="widget-content p-0">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-column align-items-start mb-2">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="h4">{{ $node->label }}</div>
                                @if($node->note)
                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-warning active btnNoteTask" data-route="{{ route('task-node.note', [$node->id]) }}">
                                        Note presenti
                                    </button>
                                @endif
                            </div>
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
                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex">
                                @if($node->data_inizio_prevista || $node->data_fine_prevista)
                                    <div class="d-flex flex-column align-items-start" style="min-width: 120px">
                                        <small>Inizio previsto</small>
                                        <span class="badge badge-light pl-0">
                                            {{ $node->data_inizio_prevista ? dataOra($node->data_inizio_prevista) : '-' }}
                                        </span>
                                    </div>

                                    <div class="d-flex flex-column  align-items-start" style="min-width: 120px">
                                        <small>Fine prevista</small>
                                        <span class="badge badge-light pl-0">
                                            {{ $node->data_fine_prevista ? dataOra($node->data_fine_prevista) : '-' }}
                                        </span>
                                    </div>
                                @endif

                                @if(!$node->data_inizio_prevista && !$node->data_fine_prevista)
                                    <div class="d-flex flex-column  align-items-start" style="min-width: 240px">
                                        <small>Date previsionali</small>
                                        <span class="badge badge-light pl-0 text-warning">
                                            non indicate
                                        </span>
                                    </div>
                                @endif

                                @if($node->started_at || $node->completed_at)
                                    <div class="d-flex flex-column align-items-start" style="min-width: 120px">
                                        <small>Inizio</small>
                                        <span class="badge badge-light pl-0">
                                            {{ $node->started_at ? dataOra($node->started_at) : '-' }}
                                        </span>
                                    </div>

                                    <div class="d-flex flex-column  align-items-start" style="min-width: 120px">
                                        <small>Fine</small>
                                        <span class="badge badge-light pl-0">
                                            {{ $node->completed_at ? dataOra($node->completed_at) : '-' }}
                                        </span>
                                    </div>
                                @endif

                                @if(!$node->started_at && !$node->completed_at)
                                    <div class="d-flex flex-column  align-items-start" style="min-width: 240px">
                                        <small>Date lavorazione</small>
                                        <span class="badge badge-light pl-0 text-warning">
                                            non indicate
                                        </span>
                                    </div>
                                @endif
                            </div>


                            <div class="d-flex justify-content-end" style="min-width: 120px">

                                {{-- Menù funzione --}}
                                <div class="d-flex justify-content-end">
                                    @can('task_uploads', $node)
                                        <button class="border-0 btn-transition btn btn-outline-link uploadDocNode"
                                            data-route="{{ route('upload-s3.modal', ['reference_id' => $node->id, 'reference_table' => 'tasks']) }}"
                                            data-toggle="tooltip" data-placement="top" data-title="Upload documenti">
                                            <i class="bx bx-archive-in"></i>
                                        </button>
                                    @endcan

                                    @can('task_mod_fasi', $el)
                                        <button class="border-0 btn-transition btn btn-outline-link modNode"
                                            data-route="{{ route('task-node.edit', $node->id) }}"
                                            data-toggle="tooltip" data-placement="top" data-title="Modifica task">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>

                                        <button
                                           class="border-0 btn-transition btn btn-outline-link text-danger deleteNode"
                                           data-route="{{ route('task-node.edit', [$node->id, 'delete' => 1]) }}"
                                           data-toggle="tooltip" data-placement="top" data-title="Elimina task">
                                            <i class="bx bx-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </li>

        @endforeach

    </ul>
