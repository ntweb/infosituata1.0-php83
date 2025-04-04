@if($node->logs->count())

    @if($node->type == 'materiale')
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Costi</th>
                    <th>Quantità</th>
                    <th>Data attr. carico</th>
                    <th>Note</th>
                    <th></th>
                    <th class="text-right">Creato</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $_total = 0;
                    $_total_qty = 0;
                @endphp
                @foreach($node->logs as $log)
                    @php
                        $_total = $_total + $log->item_costo;
                        $_total_qty = $_total_qty + $log->item_qty;

                        $routeUploadSuccessCallback = route('commessa-node.logs', [$node->id, '_render_table' => true]);
                        $uploadSuccessCallback = "refreshLogsItemTable('".$routeUploadSuccessCallback."');closeDrawer();$('#openNodeLog-".$node->id."').trigger('click');";

                        $attachmentExists = isset($attachmentsGrouped[$log->id]);
                    @endphp
                    <tr>
                        <td>
                            <small>{{ euro($log->item_costo) }} &euro;</small>
                        </td>
                        <td>
                            <small>{{ $log->item_qty }}</small>
                        </td>
                        <td>
                            <small>{{ $log->data_attribuzione ? data($log->data_attribuzione) : '-' }}</small>
                        </td>
                        <td style="font-size: 10px">{{ $log->note }}</td>
                        <td>
                            @if($attachmentExists)
                                <a href="javascript:void(0)" class="displayDocMaterialeLog mx-1"
                                   data-toggle="tooltip" data-placement="top" data-title="Visualizza documenti"
                                   data-tr="log-materiale-attachment-{{$log->id}}">
                                    <i class="bx bx-paperclip"></i>
                                </a>
                            @endif
                            <a href="javascript:void(0)" class="uploadDocMaterialeLog mx-1"
                               data-toggle="tooltip" data-placement="top" data-title="Upload documenti"
                               data-route="{{ route('upload-s3.modal', ['reference_id' => $log->id, 'reference_table' => 'commesse_log', 'render' => 'form', 'callback' => $uploadSuccessCallback]) }}">
                                <i class="bx bx-archive-in"></i>
                            </a>
                        </td>
                        <td class="text-right" style="font-size: 10px">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {{ $log->username }}
                                    <br>
                                    {{ dataOra($log->created_at) }}
                                </div>
                                <a href="javascript:void(0)" class="text-danger mx-2 deleteCommessaLog" data-route="{{ route('commessa-log.edit', [$log->id, 'delete' => true, 'commesse_id' => $node->id]) }}">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @if($attachmentExists)
                        @foreach($attachmentsGrouped[$log->id] as $a)
                            @php
                                $url = $a->url_cloud ?? route('s3.get', $a->id);
                                $trId = 'tr-log-materiale-attachment-'.$log->id;
                            @endphp
                            <tr id="{{ $trId }}" class="bg-heavy-rain log-materiale-attachment log-materiale-attachment-{{$log->id}}" style="display: none;">
                                <td colspan=5">
                                    <a href="{{ $url }}" target="_blank">
                                        @if($a->url_cloud)
                                            <i class="bx bx-cloud mr-1"></i>
                                        @endif
                                        <small>{{ Str::title($a->label) }}</small>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <a type="button" class="text-danger btnDelete"
                                       data-message="Si conferma la cancellazione?"
                                       data-route="{{ route('upload-s3.destroy', [$a->id]) }}"
                                       data-callback="closeAllModal();$('{{$trId}}').remove();{{ $uploadSuccessCallback }}">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr>
                    <td><small class="font-weight-bold">{{ euro($_total) }} &euro;</small></td>
                    <td><small class="font-weight-bold">{{ $_total_qty }}</small></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    @elseif($node->type == 'extra')
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Etichetta</th>
                    <th>Costi</th>
                    <th>Data attr.</th>
                    <th class="text-right">Creato</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $_total = 0;
                    $_total_qty = 0;
                @endphp
                @foreach($node->logs as $log)
                    @php
                        $_total = $_total + $log->item_costo;
                        $_total_qty = $_total_qty + $log->item_qty;
                    @endphp
                    <tr>
                        <td style="font-size: 10px">{{ $log->note }}</td>
                        <td>
                            <small>{{ euro($log->item_costo) }} &euro;</small>
                        </td>
                        <td>
                            <small>{{ $log->data_attribuzione ? data($log->data_attribuzione) : '-' }}</small>
                        </td>
                        <td class="text-right" style="font-size: 10px">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {{ $log->username }}
                                    <br>
                                    {{ dataOra($log->created_at) }}
                                </div>
                                <a href="javascript:void(0)" class="text-danger mx-2 deleteCommessaLog" data-route="{{ route('commessa-log.edit', [$log->id, 'delete' => true, 'commesse_id' => $node->id]) }}">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td><small class="font-weight-bold">{{ euro($_total) }} &euro;</small></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="bg-heavy-rain">
                <tr>
                    <th></th>
                    <th>Da</th>
                    <th>A</th>
                    <th class="text-center">GG lav.</th>
                    <th class="text-center">Ore lav</th>
                    <th>Note</th>
                    <th class="text-right">Creato</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $_gg = 0;
                    $_h = 0;
                @endphp
                @foreach($node->logs as $log)
                    @php
                        $gg = differenceInDays($log->inizio, $log->fine, $node->day_to_hours);
                        $h = differenceInHours($log->inizio, $log->fine);
                        $_gg = $_gg + $gg;
                        $_h = $_h + $h;
                    @endphp
                    <tr>
                        <td>
                            @if($log->in_timbrature_id)
                                <i class="bx bx-time-five" data-toggle="tooltip" data-placement="top" data-title="Proveniente da timbratura"></i>
                            @endif

                            @if($log->fl_qr)
                                <i class="bx bx-qr" data-toggle="tooltip" data-placement="top" data-title="Proveniente qr code"></i>
                            @endif
                        </td>
                        <td>
                            @if($node->type == 'utente')
                                <a href="javascript:void(0)" class="text-primary modify-log" data-toggle="tooltip" data-placement="top" data-title="Modifica dichiarazione" data-json="{{ json_encode($log) }}">
                                    <small>{{ $log->inizio ? dataOra($log->inizio) : '-' }}</small>
                                </a>
                            @else
                                <small>{{ $log->inizio ? dataOra($log->inizio) : '-' }}</small>
                            @endif
                        </td>
                        <td>
                            <small>{{ $log->fine ? dataOra($log->fine) : '-' }}</small>
                        </td>
                        <td class="text-center"><small>{{ $gg }}</small></td>
                        <td class="text-center"><small>{{ $h }}</small></td>
                        <td style="font-size: 10px">{{ $log->note }}</td>
                        <td class="text-right" style="font-size: 10px">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {{ $log->username }}
                                    <br>
                                    {{ dataOra($log->created_at) }}
                                </div>

                                @if(!$log->in_timbrature_id)
                                    <a href="javascript:void(0)" class="text-danger mx-2 deleteCommessaLog" data-route="{{ route('commessa-log.edit', [$log->id, 'delete' => true, 'commesse_id' => $node->id]) }}">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td colspan="2"></td>
                    <td class="text-center"><small class="font-weight-bold">{{ $_gg }}</small></td>
                    <td class="text-center"><small class="font-weight-bold">{{ $_h }}</small></td>
                    <td colspan="2"></td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
@else
    <div class="p-2">
        @component('layouts.components.alerts.warning')
            Nessun log disponibile
        @endcomponent
    </div>
@endif
