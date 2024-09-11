@if($items->count())
    <div class="table-responsive">
        <table class="mb-0 table table-sm">
            <thead>
            <tr>
                <th>Etichetta</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $el)
                <tr>
                    <td>{{ $el->label }}</td>
                    <td class="text-right">
                        <button type="button"
                                class="border-0 btn-transition btn btn-sm btn-outline-light addItemNode addSquadraNode"
                                data-route="{{ route('commessa-node.squadra', [$node->id, $el->id]) }}"
                                data-item-id="{{ $el->id }}"
                                data-to="{{ $node->id }}">
                            Assegna
                            <i class="bx bx-right-arrow-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    @component('layouts.components.alerts.warning')
        Nessun risultato trovato
    @endcomponent
@endif
