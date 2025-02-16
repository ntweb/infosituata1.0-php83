@if(isset($el))
    @if(count($el->utenti))
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Utenti</h5>
                <div class="table-responsive pb-10">
                    <table class="mb-0 table table-hover">
                        <thead>
                        <tr>
                            {{--                                    <th>#</th>--}}
                            <th>Cognome</th>
                            <th>Nome</th>
                            <th>Matricola</th>
                            <th>Email</th>
                            <th>Power user</th>
                            <th>Stato</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($el->utenti as $utente)
                            <tr id="l-{{ $utente->id }}">
                                {{--                                        <th scope="row">{{ $utente->id }}</th>--}}
                                <td>{{ Str::title($utente->extras1) }}</td>
                                <td>{{ Str::title($utente->extras2) }}</td>
                                <td>{{ strtoupper($utente->extras3) }}</td>
                                <td>{{ strtolower($utente->user->email) }}</td>
                                <td>
                                    @if($utente->user->power_user)
                                        @component('layouts.components.labels.success')
                                            attivo
                                        @endcomponent
                                    @else
                                        @component('layouts.components.labels.error')
                                            no
                                        @endcomponent
                                    @endif
                                </td>
                                <td>
                                    @if($utente->user->active)
                                        @component('layouts.components.labels.success')
                                            attivo
                                        @endcomponent
                                    @else
                                        @component('layouts.components.labels.error')
                                            sospeso
                                        @endcomponent
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-danger btn-sm btnDelete"
                                            data-message="Si conferma la dissociazione?"
                                            data-route="{{ route('gruppo.destroy-user', [$el->id, $utente->id, '_type' => 'json']) }}"
                                            data-callback="deleteElement('#l-{{ $utente->id }}');"><i class="fas fa-trash fa-fw"></i></button>
                                </td>
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
@endif
