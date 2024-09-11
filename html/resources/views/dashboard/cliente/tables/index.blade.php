@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Clienti</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th>Rag. Soc. / Denominazione</th>
                        <th>Cognome</th>
                        <th>Nome</th>
                        <th>P. IVA</th>
                        <th>CF</th>
                        <th>Sdi</th>
                        <th>Pec</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lr-{{ $el->id }}">
                            <td>{{ $el->rs ? Str::title($el->rs) : '-' }}</td>
                            <td>{{ $el->cognome ? Str::title($el->cognome) : '-' }}</td>
                            <td>{{ $el->nome ? Str::title($el->nome) : '-' }}</td>
                            <td>{{ $el->piva ? strtoupper($el->piva) : '-'}}</td>
                            <td>{{ $el->cf ? strtoupper($el->cf) : '-' }}</td>
                            <td>{{ $el->sdi ?? '-' }}</td>
                            <td>{{ $el->pec ? strtolower($el->pec) : '-' }}</td>
                            <td class="text-right">
                                @can('can_create_clienti')
                                    <a href="javascript:void(0)"
                                        data-route="{{ route('cliente.edit', [$el->id]) }}"
                                        class="btn btn-primary btn-sm btnEditCliente">Edit</a>
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
