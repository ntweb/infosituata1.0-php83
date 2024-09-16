@extends('pdf.base')

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
            <td class="font-size-10 text-uppercase"><b>Codice evento:</b> {{ $el->codice_evento }}</td>
        </tr>
    </table>
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
            <td class="font-size-10 text-uppercase"><b>Data</b> <br>{{ $el->data_e_ora }}</td>
            <td class="font-size-10 text-uppercase"><b>Fascia oraria</b> <br>{{ $modot_23_2024_fascia_oraria[$json_fascia_oraria] }}</td>
            <td class="font-size-10 text-uppercase"><b>Cognome e nome</b> <br>{{ $el->nome_e_cognome ?? '-' }}</td>
        </tr>
    </table>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Attività</b> <br>{{ $el->attivita }}</td>
            <td class="font-size-10 text-uppercase"><b>Descrizione incidente</b> <br>{{ $el->descrizione_incidente }}</td>
        </tr>
    </table>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Possibili cause dell'evento</b><br>
                <ul style="padding-left: 1cm; padding-right: 1cm;">
                @foreach($json_possibili_cause ?? [] as $id)
                    <li>{{ $modot_23_2024_possibili_cause[$id] }}</li>
                @endforeach
                </ul>
            </td>
        </tr>
        @if ($json_possibili_cause_altro)
        <tr>
            <td class="font-size-10">
                <b>Altro (Specifica)</b>
                <br>
                {{$json_possibili_cause_altro}}
            </td>>
        </tr>
        @endif
    </table>

    <br>
    <table width="100%">
        <tr>
            <td class="font-size-10 text-uppercase">
                <b>Firma lavoratore</b>
                <br>
                <br>
                ______________________________
            </td>
            <td class="font-size-10 text-uppercase">
                <b>Firma preposto</b>
                <br>
                <br>
                ______________________________
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <h1>A CURA DEL SERVIZIO DI PREVENZIONE E PROTEZIONE (CONSULTANDO I PREPOSTI, MC E RLS)</h1>
    <br>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Codice evento:</b> {{ $el->codice_evento }}</td>
        </tr>
    </table>

    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase">
                <b>Descrizione finale dell'evento:</b>
                <br>
                {{ $el->descrizione_finale_evento }}
            </td>
        </tr>
    </table>

    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Incidente (Tipologia di mancato infortunio)</b><br>
                <ul style="padding-left: 1cm; padding-right: 1cm;">
                    @foreach($json_incidente_poss_cause ?? [] as $id)
                        <li>{{ $modot_23_2024_incidente_poss_cause[$id] }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @if (@$json_incidente_poss_cause_altro)
            <tr>
                <td class="font-size-10">
                    <b>Altro (Specifica)</b>
                    <br>
                    {{$json_incidente_poss_cause_altro}}
                </td>>
            </tr>
        @endif
    </table>

    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Cause accertate dell'evento (A partire dal modulo di segnalazione si confermano o modificano le possibili cause lì indicate)</b><br>
                <ul style="padding-left: 1cm; padding-right: 1cm;">
                    @foreach($json_cause_accertate ?? [] as $id)
                        <li>{{ $modot_23_2024_cause_accertate[$id] }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @if (@$json_cause_accertate_altro)
            <tr>
                <td class="font-size-10">
                    <b>Altro (Specifica)</b>
                    <br>
                    {{$json_cause_accertate_altro}}
                </td>>
            </tr>
        @endif
        <tr>
            <td class="font-size-10 text-uppercase"><b>La situazione rilevata si è già presentata in passato anche recente?</b><br>
                {{ $modot_23_2024_situazione_presentata[$json_situazione_presentata] }}
            </td>
        </tr>
        @if (@$json_critic_organizzative)
        <tr>
            <td class="font-size-10 text-uppercase"><b>Cause accertate dell'evento (A partire dal modulo di segnalazione si confermano o modificano le possibili cause lì indicate)</b><br>
                <ul style="padding-left: 1cm; padding-right: 1cm;">
                    @foreach($json_critic_organizzative ?? [] as $id)
                        <li>{{ $modot_23_2024_critic_organizzative[$id] }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endif
        <tr>
            <td class="font-size-10 text-uppercase"><b>Danni</b><br>
                <p>Danno a strutture, impianti, attrezzature:
                    {{ $modot_23_2024_danno[$json_danno] }}
                </p>
                <p>Potenziale danno alle persone:
                    {{ $modot_23_2024_potenziale_danno[$json_potenziale_danno] }}
                </p>
            </td>
        </tr>
    </table>

    <br>
    <table width="100%">
        <tr>
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

    <div class="page-break"></div>

    <h1>A CURA DEL SERVIZIO DI PREVENZIONE E PROTEZIONE (CONSULTANDO I PREPOSTI, MC E RLS)</h1>
    <br>
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Codice evento:</b> {{ $el->codice_evento }}</td>
        </tr>
    </table>

    @php
        $azioni = [
            'Tecnico' => 'azioni_migl_prev_tecnico',
            'Formazione' => 'azioni_migl_prev_formazione',
            'Definizione' => 'azioni_migl_prev_definizione',
            'Verifica' => 'azioni_migl_prev_verifica',
            'Altro' => 'azioni_migl_prev_altro'
        ];

        $areAzioni = false;
        foreach ($azioni as $azione) {
            if ($el->$azione) {
                $areAzioni = true;
                break;
            }
        }
    @endphp
    @if($areAzioni)
    <table width="100%" class="bordered">
        <tr>
            <td class="font-size-10 text-uppercase"><b>Azioni di miglioramento (correttive, preventive) - Tipologia intervento</b></td>
        </tr>
        @foreach($azioni as $title => $azione)
            <tr>
                <td class="font-size-10 text-uppercase">
                    <b>{{ $title }}</b>
                    <br>
                    {{ $el->$azione }}
                </td>
            </tr>
        @endforeach
    </table>
    @endif

    <br>
    <h1>Verifica (follow up) azioni intraprese</h1>
    <br>

    @for($i = 1; $i <= 3; $i++)
        @php
            $json_f_up_azioni = 'json_f_up_azioni_'.$i;
            $json_f_up_resp = 'json_f_up_resp_'.$i;
            $json_f_up_entro = 'json_f_up_entro_'.$i;
            $json_f_up_data_att = 'json_f_up_data_att_'.$i;
        @endphp
        <table width="100%" class="bordered">
            <tr>
                <td class="font-size-10 text-uppercase" colspan="5"><b>Azioni di miglioramento (correttive, preventive) #{{$i}}</b></td>
            </tr>
            <tr>
                <td class="font-size-10 text-uppercase" colspan="5">
                    <p>{{ $$json_f_up_azioni ?? '-' }}</p>
                </td>
            </tr>
            <tr>
                <td class="font-size-10 text-uppercase">
                    <b style="font-size: 10px">Responsabile attuazione</b><br>
                    <p>{{ $$json_f_up_resp ?? '-' }}</p>
                </td>
                <td class="font-size-10 text-uppercase">
                    <b style="font-size: 10px">Entro il</b><br>
                    <p>{{ $$json_f_up_entro ? \Carbon\Carbon::parse($$json_f_up_entro)->format('d-m-Y') : '-' }}</p>
                </td>
                <td class="font-size-10 text-uppercase">
                    <b style="font-size: 10px">Firma presa in carico</b><br>
                    <p>&nbsp;</p>
                </td>
                <td class="font-size-10 text-uppercase">
                    <b style="font-size: 10px">Data attuazione</b><br>
                    <p>{{ $$json_f_up_data_att ? \Carbon\Carbon::parse($$json_f_up_data_att)->format('d-m-Y') : '-' }}</p>
                </td>
                <td class="font-size-10 text-uppercase">
                    <b style="font-size: 10px">Verifica attuazione Data e firma</b><br>
                    <p>&nbsp;</p>
                </td>
            </tr>

        </table>
    @endfor

@endsection
