@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Import da excel', 'icon' => 'bx bx-import'])
        Clienti procedura di import
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">


            @if(!$exist && !$toDoImport->count())
            <div class="main-card mb-2 card">
                <div class="card-header">Tracciato per import</div>
                <div class="card-body">
                    <p>
                        Il file da importare per eseguire la procedura di inserimento/aggiornamento dei clienti deve avere il seguente tracciato <a href="{{ url('download/tracciato-import-clienti.xlsx') }}">Scarica tracciato Excel</a>.
                        <br>
                        È importante rispettare l'intestazione del file, non alterando il nome delle colonne e l'estensione (i file compatibili devono essere Excel <strong>.xlsx</strong>)
                    </p>
                </div>
            </div>

            <form action="{{ route('cliente.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="main-card mb-3 card">
                    <div class="card-header">File da importare</div>
                    <div class="card-body">
                        <div class="row">
                            @component('layouts.components.forms.file-upload', ['name' => 'attachment', 'class' => 'col-md-12', 'value' => null, 'accept' => '.xls,.xlsx'])
                                Carica il file da importare
                            @endcomponent
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">Carica il file</button>
                    </div>
                </div>
            </form>
            @else

                @if($exist)
                <div class="main-card mb-2 card">
                    <div class="card-header">File caricato</div>
                    <div class="card-body">
                        @if(!$exist->error)
                            <p>
                                È in corso l'import del file caricato, ritornare su questa pagina tra qualche minuto per controllare i risultati
                            </p>
                        @else
                            <p class="text-danger">
                                Errore in fase di importazione, file non conforme.
                                <br>
                                <code>{{ $exist->error }}</code>
                            </p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('cliente.import-cancel') }}" class="btn btn-warning">Annulla l'import</a>
                    </div>
                </div>
                @endif

                @if($toDoImport->count())
                    <div class="main-card mb-2 card">
                        <div class="card-header">Clienti da importare / aggiornare</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Rag. sociale</th>
                                                    <th>cognome</th>
                                                    <th>nome</th>
                                                    <th>piva</th>
                                                    <th>cf</th>
                                                    <th>indirizzo</th>
                                                    <th>cap</th>
                                                    <th>citta</th>
                                                    <th>provincia</th>
                                                    <th>telefono</th>
                                                    <th>sdi</th>
                                                    <th>pec</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($toDoImport as $cl)
                                                <tr>
                                                    <td>{{ $cl->rs }}</td>
                                                    <td>{{ $cl->cognome }}</td>
                                                    <td>{{ $cl->nome }}</td>
                                                    <td>{{ $cl->piva }}</td>
                                                    <td>{{ $cl->cf }}</td>
                                                    <td>{{ $cl->indirizzo }}</td>
                                                    <td>{{ $cl->cap }}</td>
                                                    <td>{{ $cl->citta }}</td>
                                                    <td>{{ $cl->provincia }}</td>
                                                    <td>{{ $cl->telefono }}</td>
                                                    <td>{{ $cl->sdi }}</td>
                                                    <td>{{ $cl->pec }}</td>
                                                    <td class="text-right">
                                                        @if($cl->tipo_operazione == 'error')
                                                            @component('layouts.components.labels.error')
                                                                errore
                                                            @endcomponent
                                                        @elseif ($cl->tipo_operazione == 'update')
                                                            @component('layouts.components.labels.warning')
                                                                aggiornamento
                                                            @endcomponent
                                                        @else
                                                            @component('layouts.components.labels.success')
                                                                inserimento
                                                            @endcomponent
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($cl->log)
                                                            <small>{{ $cl->log }}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                              @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <div>
                                <a href="{{ route('cliente.import-do') }}" class="btn btn-success mr-1">Importa</a>
                                <a href="{{ route('cliente.import-cancel') }}" class="btn btn-warning">Annulla l'import</a>
                            </div>
                            <div class="text-right">
                                Le righe con <span class="badge badge-pill badge-danger">errore</span> non saranno importate
                            </div>
                        </div>
                    </div>
                @endif

            @endif
        </div>
    </div>
@endsection
