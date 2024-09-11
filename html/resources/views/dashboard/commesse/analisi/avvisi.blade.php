@if(count($listAvvisiCommessa))
    <div class="p-2">
        <div class="table-responsive">
            <table class="mb-0 table table-sm" id="allegati_dt">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Etichetta</th>
                    <th>Data avviso</th>
                    <th>Avvisa entro gg</th>
                    <th>Avvisa i seguenti gruppi</th>
                    <th><i class="bx bx-check"></i> Check</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($listAvvisiCommessa as $el)
                    <tr>
                        <td>{{ Str::title($el->label) }} </td>
                        <td>{{ data($el->start_at) }}</td>
                        <td>{{ $el->avvisa_entro_gg }}</td>
                        <td>
                            @if(count($el->gruppi))
                                @component('layouts.components.labels.info')
                                    {{ $el->gruppi->first()->label }}
                                @endcomponent
                                @if(count($el->gruppi) > 1)
                                    @component('layouts.components.labels.info')
                                        ...
                                    @endcomponent
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($el->checked_at)
                                @component('layouts.components.labels.success')
                                    {{ data($el->checked_at) }}
                                @endcomponent
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm createAvvisoCommessa" data-route="{{ route('scadenzario.show-commessa', $el->id) }}" data-callback="refreshAvvisi();">
                                <i class="bx bx-pencil"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm btnDelete" data-route="{{ route('scadenzario.destroy-commessa', $el->id) }}" data-callback="refreshAvvisi();">
                                <i class="bx bx-trash"></i>
                            </a>
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
            Nessun avviso trovato
        @endcomponent
    </div>
@endif

