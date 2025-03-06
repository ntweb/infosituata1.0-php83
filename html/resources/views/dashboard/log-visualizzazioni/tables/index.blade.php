@if(count($list))
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="d-flex justify-content-between my-2">
                <h5 class="card-title">Log visualizzazioni</h5>
                <a href="{{ $filename }}" class="btn btn-sm btn-success">Export</a>
            </div>
            <div class="table-responsive pb-10">
                <table class="mb-0 table table-hover" id="dashboard_user_index">
                    <thead>
                    <tr>
                        <th>Utente</th>
                        <th>Visualizzazione</th>
                        <th></th>
                        <th>Effettuata il</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $el)
                        <tr id="lr-{{ $el->id }}">
                            <td>{{ Str::title($el->utente->label) }}</td>
                            <td>{{ Str::title($el->risorsa->label) }}</td>
                            <td>{{ Str::title($el->risorsa->controller) }}</td>
                            <td>{{ dataOra($el->created_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    @component('layouts.components.alerts.warning')
        Nessun elemento trovato
    @endcomponent
@endif
