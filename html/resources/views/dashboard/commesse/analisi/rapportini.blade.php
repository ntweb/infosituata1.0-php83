@if(count($list))
    <div class="p-2">
        <div class="table-responsive">
            <table class="mb-0 table table-sm" id="rapportini_dt">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Etichetta</th>
                    <th>Descrizione</th>
                    <th>Fase / sottofase</th>
                    <th class="text-right">Data riferimento</th>
                    <th class="text-right">Livello priorità</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $el)
                    <tr class="pointer openRapportino @if($loop->last) openRapportinoLast @endif" data-route="{{ route('commessa-rapportino.show', [$el->id, '_modal' => true]) }}">
                        <td>{{ Str::title($el->titolo) }}</td>
                        <td><small>{{ Str::limit($el->descrizione, 50, '...') }}</small></td>
                        <td>{{ Str::title($el->fase->label) }}</td>
                        <td class="text-right">{{ data($el->start) }}</td>
                        <td class="text-right">
                            @component('dashboard.commesse.components.labels.rapportino-livello', ['el' => $el])
                            @endcomponent
                        </td>
                        <td>
                            <div class="d-flex flex-column align-items-end">
                                <small>{{ $el->created_at }}</small>
                                <small>{{ $el->username }}</small>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="p-4">
        @component('layouts.components.alerts.info')
            Nessun rapportino trovato
        @endcomponent
    </div>
@endif

