@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei task assegnati', 'icon' => 'bx bx-task', 'right_component' => 'dashboard.tasks.components.assegnati-header'])
        Task manager
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 table-responsive" id="div-list-assegnati">

            @if(count($list))
                <div class="main-card mb-3 card" style="min-width: 900px">
                    <ul class="list-group list-group-flush task-ul">

                        @foreach($list as $node)
                            {{-- Fase  --}}
                            <li class="list-group-item py-2 pt-4" @if($node->started_at && $node->completed_at) style="background: #bde5bd" @endif @if($node->started_at && !$node->completed_at) style="background: #f9ddcc" @endif>
                                <div class="widget-content p-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-column w-100 align-items-start mb-2">
                                                <div class="d-flex flex-row w-100 justify-content-between">
                                                    <div class="h4">Attività: {{ $node->label }}</div>
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

                                                @if($node->root->note)
                                                    <div>
                                                        <strong>Note:</strong> <span class="font-italic">{{ $node->root->note }}</span>
                                                    </div>
                                                @endif

                                                @if($node->root->indirizzo_specifico)
                                                    <div>
                                                        <strong>Indirizzo spec:</strong> <span class="font-italic">{{ $node->root->indirizzo_specifico }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($node->root->cliente)
                                                <div class="d-flex flex-column align-items-end">
                                                    <div class="h4">Cliente: {{ $node->root->cliente->rs }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">

                                            <div class="d-flex">
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


                                            <div class="d-flex justify-content-end" style="min-width: 120px">

                                                {{-- Menù funzione --}}
                                                <div class="d-flex justify-content-end">

                                                    @if(!$node->started_at)
                                                    <button class="border-0 btn-transition btn btn-success btnIniziaTask mr-1"
                                                            data-route="{{ route('task-node.started', [$node->id]) }}">
                                                        <i class="bx bx-play"></i> Inizia task
                                                    </button>
                                                    @endif

                                                    @if($node->started_at && !$node->completed_at)
                                                    <button class="border-0 btn-transition btn btn-danger btnTerminaTask mr-1"
                                                            data-route="{{ route('task-node.completed', [$node->id]) }}">
                                                        <i class="bx bx-stop"></i> Termina task
                                                    </button>
                                                    @endif

                                                    <button class="border-0 btn-transition btn btn-light btnNoteTask mr-1"
                                                            data-route="{{ route('task-node.note', [$node->id]) }}">
                                                        <i class="bx bx-edit-alt"></i> Scrivi note
                                                    </button>

                                                    @can('task_uploads', $node)
                                                        <button class="border-0 btn-transition btn btn-light uploadDocNode"
                                                                data-route="{{ route('upload-s3.modal', ['reference_id' => $node->id, 'reference_table' => 'tasks']) }}"
                                                                data-toggle="tooltip" data-placement="top" data-title="Upload documenti">
                                                            <i class="bx bx-upload"></i> Carica documenti
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
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessun elemento trovato
                @endcomponent
            @endif
        </div>
    </div>

@endsection

@section('modal')
    @include('dashboard.tasks.modals.tasks-search')
@endsection
