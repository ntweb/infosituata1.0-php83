@extends('pdf.commessa.base')

@section('style')
    <style type="text/css">
        header { border-bottom: solid 0.2mm #000 }
        footer { border-top: solid 0.2mm #000 }
    </style>
@endsection

@section('content')

    <main>

        <div class="full-width mt-5 text-center">
            <h1>Rapportino</h1>
        </div>

        <table class="full-width mt-5 bordered" page-break-inside: auto;>
            <thead>
            <tr>
                <th class="vertical-align-top bgLightGrey text-left" colspan="2">
                    <strong>{{ Str::title($r->label) }}</strong>
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
                <td colspan="3">Livello priorità: {{ $r->livello }}</td>
            </tr>
            <tr>
                <td colspan="2">Redatto da: {{ Str::title($r->username) }}</td>
                <td class="text-right">Creato il {{ dataOra($r->created_at) }}</td>
            </tr>
            <tr>
                <td colspan="2">Inviato per conoscenza a</td>
                <td class="">
                    <ul>
                        @foreach($users as $user)
                            <li>{{ Str::title($user->name) }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>

    </main>


@endsection

{{-- Footer --}}
<header>
    <table class="full-width mt-5">
        <tr>
            <td class="text-left" style="width: 50%">
                <span class="font-size-11">
                    <b>Rapportino: {{ Str::title($r->titolo) }}</b>
                </span>
            </td>
            <td class="text-right">
                <span class="font-size-6 text-uppercase">Stampa rapportino</span>
            </td>
        </tr>
    </table>
</header>

{{-- Footer --}}
<footer>
    <table class="full-width">
        <tr>
            <td class="text-left" style="width: 50%">
                <br>
                <span class="font-size-8">
                    {{ Str::title($azienda->label) }}
                </span>
            </td>
            <td class="text-right">
                <em>
                    <span class="font-size-8">
                        pagina creata il {{ date('d/m/Y') }}
                    </span>
                </em>
            </td>
        </tr>
    </table>
</footer>
