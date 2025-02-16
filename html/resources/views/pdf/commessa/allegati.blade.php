    <main>

        <div class="full-width mt-5 text-center">
            <h1>Elenco allegati</h1>
        </div>

        @if(count($allegati))

                <table class="full-width mt-5 bordered" page-break-inside: auto;>
                    <thead>
                    <tr>
                        <th class="vertical-align-top bgLightGrey text-left" colspan="3">
                            <strong>Elenco allegati</strong>
                        </th>
                    </tr>
                    </thead>
                    @foreach($allegati as $a)
                    <tr>
                        <td>
                            <strong>{{ Str::title($a->node->label) }}</strong>
                        </td>
                        <td>{{ Str::title($a->label) }}</td>
                        <td>{{ $a->filename }}</td>
                    </tr>
                    @endforeach
                </table>
        @else
            <p>Nessun allegato presente</p>
        @endif

    </main>
