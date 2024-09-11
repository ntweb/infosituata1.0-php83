@if($elements->count())
    <div class="table-responsive">
        <table class="table table-sm w-100">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Costo orario <i class="bx bx-euro"></i></th>
            </tr>
            </thead>
            @foreach($elements as $sq)
            <tr id="squadra-item-id-{{ $sq->item->id }}">
                <td>
                    <button type="button"
                            class="mr-2 btn-transition btn btn-outline-link text-danger btnSquadraItemDelete"
                            data-route="{{ route('squadra-item.destroy', [$sq->id, 'squadra_id' => $squadra_id]) }}">
                        <i class="bx bx-trash"></i>
                    </button>
                    <span>{{ \Illuminate\Support\Str::title($sq->item->label) }}</span>
                </td>
                <td>{{ $sq->item->controller }}</td>
                <td style="width: 180px">
                    <input name="costo_item_orario_previsto[{{ $sq->id }}]" type="number" class="form-control form-control-sm" value="{{ $sq->costo_item_orario_previsto }}" min="0">
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@else
    @component('layouts.components.alerts.info')
        Nessun elemento associato
    @endcomponent
@endif
