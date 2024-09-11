@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="card-title">Richieste permessi / ferie</h5>
                @if(isset($export))
                    <a href="{{ request()->getRequestUri() }}&export=1" class="btn btn-sm btn-success">Export</a>
                @endif
            </div>


            <div class="table-responsive">
                <table class="mb-0 table table-hover">
                    <thead>
                    <tr>
                        <th>Utente</th>
                        <th>Tipologia</th>
                        <th>Giorni</th>
                        <th class="text-right">Stato</th>
                        <th class="text-right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $p)
                        <tr class="pointer">
                            <td>{{ Str::title($p->user->name) }}</td>
                            <td>{{ Str::title($p->type) }}</td>
                            <td>
                                @component('layouts.components.timbratura.giorni-permesso', ['permesso' => $p])
                                @endcomponent
                            </td>
                            <td class="text-right">
                                @component('layouts.components.timbratura.stato-permesso', ['permesso' => $p])
                                @endcomponent
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-sm btn-light openModalPermesso" data-route="{{ route('timbrature-permessi.edit', $p->id) }}">
                                    Edit
                                </button>

                                <button type="button" class="btn btn-sm btn-danger btnDelete"
                                        data-message="Si conferma la cancellazione?"
                                        data-route="{{ route('timbrature-permessi.destroy', [$p->id, '_type' => 'json']) }}"
                                        data-callback="refreshPermessiTable();"><i class="fas fa-trash fa-fw"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@else
    @component('layouts.components.alerts.warning')
        Nessun permesso richiesto
    @endcomponent
@endif
