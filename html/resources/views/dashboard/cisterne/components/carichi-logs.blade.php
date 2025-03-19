
        @if(count($carichi))
        <div class="table-responsive">
            <table class="mb-0 table table-hover">
                <thead>
                <tr>
                    <th>Litri</th>
                    <th class="text-right">Prezzo/Litri</th>
                    <th class="text-right">Data carico</th>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($carichi as $el)
                    <tr>
                        <td>
                            @if($el->note)
                            <a tabindex="0" class="note" role="button" data-toggle="popover" data-trigger="focus" title="Note" data-content="{{ $el->note }}">
                                {{ $el->litri }}
                            </a>
                            @else
                                {{ $el->litri }}
                            @endif
                        </td>
                        <td class="text-right">{{ euro($el->prezzo) }} &euro;</td>
                        <td class="text-right">{{ data($el->created_at) }}</td>
                        <td class="text-right">
                            @if ($loop->index == 0)
                            <button type="button" class="btn btn-sm btn-danger btnDelete"
                                    data-message="Si conferma la cancellazione del carico?"
                                    data-route="{{ route('cisterne.carico-destroy', [$el->cisterne_id, $el->id, '_type' => 'json']) }}"
                                    data-callback="window.location.reload(true);"><i class="fas fa-trash fa-fw"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="alert alert-info">
                Nessun carico presente
            </p>
        @endif
