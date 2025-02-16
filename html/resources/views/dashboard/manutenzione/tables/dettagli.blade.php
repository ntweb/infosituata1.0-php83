@if(count($dettagli))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Dettagli manutenzione</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover">
                    <thead>
                    <tr>
                        <th>Ricambi</th>
                        <th>Magazzino</th>
                        <th>Acquistati</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dettagli as $l)
                        <tr id="l-{{ $l->id }}">
                            <td>{{ Str::title($l->ricambio->label) }}</td>
                            <td>{{ $l->magazzino }}</td>
                            <td>{{ $l->acquistati }}</td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-danger btnDelete"
                                            data-message="Si conferma la cancellazione?"
                                            data-route="{{ route('manutenzione-dettaglio.destroy', [$l->id, '_type' => 'json']) }}"
                                            data-callback="deleteElement('#l-{{ $l->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                    <button type="button" class="btn btn-light get-html"
                                            data-route="{{ route('manutenzione-dettaglio.edit', [$l->id]) }}"
                                            data-container="#form-create"><i class="fas fa-edit fa-fw"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
