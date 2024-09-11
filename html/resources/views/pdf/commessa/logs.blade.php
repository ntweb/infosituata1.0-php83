    <main>

        <div class="full-width mt-5 text-center">
            <h1>Log risorse</h1>
        </div>

        @foreach($logs as $nodes)
            @php
                $label = $nodes->first()->label;
            @endphp
            <table class="full-width mt-5 bordered" page-break-inside: auto;>
                <thead>
                <tr class="bgLightGrey">
                    <th class="vertical-align-top text-left" width="40%">
                        <strong>{{ Str::title($label) }}</strong>
                    </th>
                    <th class="vertical-align-top text-right" width="20%">Inizio</th>
                    <th class="vertical-align-top text-right" width="20%">Fine</th>
                    <th class="vertical-align-top text-right" width="20%">Costo</th>
                </tr>
                </thead>
                @foreach($nodes as $node)
                    @if($node->logs->count())
                        @foreach($node->logs as $log)
                            <tr>
                                <td>{{ Str::title($log->commessa->parent->label) }}</td>
                                <td class="text-right">{{ $log->inizio ? dataOra($log->inizio) : '-' }}</td>
                                <td class="text-right">{{ $log->fine ? dataOra($log->fine) : '-' }}</td>
                                <td class="text-right">{{ euro(costoConsuntivoSingoloLogItem($node, $log)) }} €</td>
                            </tr>
                        @endforeach
                    @endif
              @endforeach
            </table>
        @endforeach

    </main>
