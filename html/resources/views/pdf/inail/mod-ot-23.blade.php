@extends('base')

@section('style')
    <style type="text/css">

        body {
            margin-top: 1cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 1cm;
        }

    </style>
@endsection

@section('content')
    <h1>MODULO MOS 01-02 RELAZIONE DI QUASI INFORTUNIO</h1>
    <br>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Reparto:</b> {{ $el->reparto }}</td>
            <td class="font-size-10 text-uppercase"><b>Ditta coinvolta:</b> {{ $el->azienda->label }}</td>
            <td class="font-size-10 text-uppercase"><b>Anno:</b> {{ $el->anno }}</td>
        </tr>
    </table>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>N.</b> <br>{{ $el->n }}</td>
            <td class="font-size-10 text-uppercase"><b>Data e ora</b> <br>{{ $el->data_e_ora }}</td>
            <td class="font-size-10 text-uppercase"><b>Cognome e nome</b> <br>{{ $el->nome_e_cognome }}</td>
            <td class="font-size-10 text-uppercase"><b>Qualifica</b> <br>{{ $el->qualifica }}</td>
            <td class="font-size-10 text-uppercase"><b>Descrizione incidente</b> <br>{{ $el->descrizione_incidente }}
            </td>
        </tr>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td class="no-border" width="25%"></td>
            <td class="no-border" width="25%"></td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma lavoratore</b>
                <br>
                <br>
                ______________________________
            </td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma preposto</b>
                <br>
                <br>
                ______________________________
            </td>
        </tr>
    </table>

    <br>
    <h2>A CURA DEL SERVIZIO DI PREVENZIONE E PROTEZIONE (CONSULTANDO I PREPOSTI, MC E RLS)</h2>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase">
                <b>Analisi delle cause che hanno generato il problema:</b>
                <br>
                {{ $el->analisi_cause_problema }}
            </td>
        </tr>
    </table>

    <br>
    <h2>DESCRIZIONE DELLE AZIONI CORRETTIVE SE NECESSARIE</h2>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase" width="33.3333%">
                <b>Azioni da intraprendere</b>
                <br>
                {{ $el->azioni_da_intr }}
            </td>
            <td class="font-size-10 text-uppercase" width="33.3333%">
                <b>Responsabile attuazione</b>
                <br>
                {{ $el->resp_attuazione }}
            </td>
            <td class="font-size-10 text-uppercase" width="33.3333%">
                <b>Termine di attuazione</b>
                <br>
                {{ $el->term_attuazione }}
            </td>
        </tr>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td class="no-border" width="25%"></td>
            <td class="no-border" width="25%"></td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma RSPP</b>
                <br>
                <br>
                ______________________________
            </td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma datore di lavoro</b>
                <br>
                <br>
                ______________________________
            </td>
        </tr>
    </table>

    <br>
    <h2>VERIFICA DELL'EFFICACIA DELLE AZIONI INTRAPRESE</h2>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Le azioni correttive previste sono state attuate</b></td>
            <td class="font-size-10 text-uppercase text-center">SI</td>
            <td class="font-size-10 text-uppercase text-center">NO</td>
        </tr>
        <tr>
            <td class="font-size-10 text-uppercase"><b>Le azioni correttive attuate sono risultate efficaci</b></td>
            <td class="font-size-10 text-uppercase text-center">SI</td>
            <td class="font-size-10 text-uppercase text-center">NO</td>
        </tr>
        <tr>
            <td class="font-size-10 text-uppercase"><b>La problematica può considerarsi chiusa efficacemente</b></td>
            <td class="font-size-10 text-uppercase text-center">SI</td>
            <td class="font-size-10 text-uppercase text-center">NO</td>
        </tr>
        <tr>
            <td class="font-size-10 text-uppercase" colspan="3"><b>Commenti</b></td>
        </tr>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td class="no-border" width="25%"></td>
            <td class="no-border" width="25%"></td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma RSPP</b>
                <br>
                <br>
                ______________________________
            </td>
            <td class="font-size-10 text-uppercase" width="25%">
                <b>Firma datore di lavoro</b>
                <br>
                <br>
                ______________________________
            </td>
        </tr>
    </table>

    <br>
    <table width="50%">
        <tr>
            <td class="font-size-10 text-uppercase">
                <b>N° Progressivo / Anno:</b> {{ $el->n }} / {{ $el->anno }}
            </td>
        </tr>
    </table>

@endsection
