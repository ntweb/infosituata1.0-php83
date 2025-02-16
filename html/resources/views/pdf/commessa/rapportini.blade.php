    <main>

        <div class="full-width mt-5 text-center">
            <h1>Rapportini</h1>
        </div>

        @if(count($rapportini))
            @foreach($rapportini as $r)
                <table class="full-width mt-5 bordered" page-break-inside: auto;>
                    <thead>
                    <tr>
                        <th class="vertical-align-top bgLightGrey text-left" colspan="2">
                            <strong>{{ Str::title($r->fase->label) }}</strong>
                        </th>
                        <th  class="text-right" width="30%">Data rif: {{ data($r->start) }}</th>
                    </tr>
                    </thead>
                    <tr>
                        <td colspan="3">
                            <strong>{{ Str::title($r->titolo) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            {!! nl2br(Str::title($r->descrizione)) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Redatto da: {{ Str::title($r->username) }}</td>
                        <td class="text-right">Creato il {{ dataOra($r->created_at) }}</td>
                    </tr>
                </table>
            @endforeach
        @else
            <p>Nessun rapportino presente</p>
        @endif

    </main>
