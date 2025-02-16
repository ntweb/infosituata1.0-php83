@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Rapportini</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th>Rapportino</th>
                        <th>Associato a</th>
                        <th>Redatto da</th>
                        <th>Creata il</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lr-{{ $el->id }}">
                            <td>{{ Str::title($el->titolo) }}</td>
                            <td>{{ $el->item ? Str::title($el->item->label) : '-' }}</td>
                            <td>{{ $el->user->name }}</td>
                            <td>{{ dataOra($el->created_at) }}</td>
                            <td class="text-right">
                                @component('dashboard.commesse.components.labels.rapportino-livello', ['el' => $el])
                                @endcomponent
                            </td>
                            <td class="text-right">

                                @can('can-delete-rapportino', $el)
                                    <button type="button" class="btn btn-danger btn-sm btnDelete"
                                            data-message="Si conferma la cancellazione?"
                                            data-route="{{ route('rapportini.destroy', [$el->id, '_type' => 'json']) }}"
                                            data-callback="deleteElement('#lr-{{ $el->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                @endcan

                                <a href="{{ route('rapportini.show', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{ $list->links('vendor.pagination.default') }}

        </div>
    </div>
@else
    @component('layouts.components.alerts.warning')
        Nessun elemento trovato
    @endcomponent
@endif
