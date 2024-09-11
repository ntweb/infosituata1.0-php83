@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Checklist</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th>Checklist</th>
                        <th>Associata a</th>
                        <th>Redatta da</th>
                        <th>Creata il</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lcl-{{ $el->id }}" data-route="">
                            <td>{{ Str::title($el->tpl->label) }}</td>
                            <td>{{ $el->item ? Str::title($el->item->label) : 'Generica' }}</td>
                            <td>{{ $el->user->name }}</td>
                            <td>{{ dataOra($el->created_at) }}</td>
                            <td class="text-right">
                                @can('can-delete-checklist', $el)
                                    <button type="button" class="btn btn-danger btn-sm btnDelete"
                                            data-message="Si conferma la cancellazione?"
                                            data-route="{{ route('checklist.destroy', [$el->id, '_type' => 'json']) }}"
                                            data-callback="deleteElement('#lcl-{{ $el->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                @endcan

                                <a href="javascript:void(0)" data-route="{{ route('checklist.show', $el->id) }}" class="btn btn-primary btn-sm showChecklistGenerica">Edit</a>
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
