<div class="modal fade" id="modalNodeLogs" tabindex="-1" role="dialog" aria-labelledby="modalNodeLogs" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $node->label }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="node-create-body">

                <div class="row">
                    <div class="col-12 pb-0">
                        @if($node->logs->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="bg-heavy-rain">
                                    <tr>
                                        <th>Stato</th>
                                        <th>Da</th>
                                        <th>A</th>
                                        <th>Note</th>
                                        <th class="text-right">User</th>
                                        <th class="text-right">Creato</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($node->logs as $log)
                                        <tr>
                                            <td>
                                                @component('dashboard.commesse.components.labels.node-label-stato', ['node' => $log])
                                                @endcomponent
                                            </td>
                                            <td>
                                                {{ $log->inizio ? dataOra($log->inizio) : '-' }}
                                            </td>
                                            <td>
                                                {{ $log->fine ? dataOra($log->fine) : '-' }}
                                            </td>
                                            <td style="font-size: 12px">{{ $log->note }}</td>
                                            <td class="text-right" style="font-size: 12px">{{ $log->username }}</td>
                                            <td class="text-right" style="font-size: 12px">{{ dataOra($log->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-2">
                                @component('layouts.components.alerts.warning')
                                    Nessun log disponibile
                                @endcomponent
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
