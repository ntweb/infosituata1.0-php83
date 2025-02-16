@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Fatture</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lr-{{ $el->id }}">
                            <td>{{ $el->id }}</td>
                            <td class="text-right">
                                @can('can_create_fattura')
                                    <a href="javascript:void(0)"
                                        data-route="{{ route('fattura.edit', [$el->id]) }}"
                                        class="btn btn-primary btn-sm btnEditFattura">Edit</a>
                                @endcan
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
