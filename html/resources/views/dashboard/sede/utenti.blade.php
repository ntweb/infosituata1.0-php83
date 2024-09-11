
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($el->utenti as $el)
                                    <tr>
                                        {{--                                        <th scope="row">{{ $el->id }}</th>--}}
                                        <td>{{ Str::title($el->extras1) }}</td>
                                        <td>{{ Str::title($el->extras2) }}</td>
                                        <td>{{ strtoupper($el->extras3) }}</td>
                                        <td>{{ strtolower($el->user->email) }}</td>
                                        <td>
                                            @if($el->user->power_user)
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
                                            @if($el->user->active)
                                                @component('layouts.components.labels.success')
                                                    attivo
                                                @endcomponent
                                            @else
                                                @component('layouts.components.labels.error')
                                                    sospeso
                                                @endcomponent
                                            @endif
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
