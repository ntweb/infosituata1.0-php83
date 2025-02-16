@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">IVA ed esenzioni</h5>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Iva</th>
                        <th>Descr.</th>
                        <th>Descr. estesa</th>
                        <th>Natura</th>
                        <th>Esenzione</th>
                        <th>Spese bollo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lr-{{ $el->id }}">
                            <td>{{ $el->codice }}</td>
                            <td>{{ $el->iva ? $el->iva . '%' : '-' }}</td>
                            <td>{{ $el->descrizione ?? '-' }}</td>
                            <td>{{ $el->descrizione_estesa ?? '-' }}</td>
                            <td>{{ $el->natura ?? '-' }}</td>
                            <td>{{ $el->fl_esezione ? 'Si' : 'No' }}</td>
                            <td>{{ $el->fl_spese_bollo ? 'Si' : 'No' }}</td>
                            <td class="text-right">
                                @if($el->azienda_id)
                                    @can('can_create_iva')
                                        <a href="javascript:void(0)"
                                            data-route="{{ route('iva.edit', [$el->id]) }}"
                                            class="btn btn-primary btn-sm btnEditIva">Edit</a>
                                    @endcan
                                @else
                                    <button type="button" class="btn btn-light btn-sm" disabled=>
                                        Voce di sistema
                                    </button>
                                @endif
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
