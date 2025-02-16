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
            <h1>Checklist</h1>
        </div>

        <table class="full-width mt-5 bordered" page-break-inside: auto;>
                    <thead>
                    <tr>
                        <th class="vertical-align-top bgLightGrey text-left" colspan="2">
                            <strong>{{ Str::title($r->tpl->label) }}</strong>
                        </th>
                        <th class="vertical-align-top bgLightGrey text-left">
                            -
                        </th>
                    </tr>
                    </thead>
                    @foreach($tpl->children as $section)
                        <tr>
                            <td colspan="3" class="pt-2 pb-2 bgLightGrey">
                                <strong>{{ $section->label }}</strong>
                                <p>{{ $section->description }}</p>
                            </td>
                        </tr>
                        @foreach($section->children as $field)
                            @php
                                $checklistData = $r->data->groupBy('key')->toArray();
                            @endphp
                            <tr>
                                <td colspan="2">
                                    <strong>{{ $field->label }}</strong>
                                </td>
                                <td>
                                    @if($field->type == 'input')
                                        {{ @$checklistData[$field->key][0]['value'] }}
                                    @elseif($field->type == 'textarea')
                                        {{ @$checklistData[$field->key][0]['value_big'] }}
                                    @elseif($field->type == 'date')
                                        {{ @$checklistData[$field->key][0]['value'] ? data(@$checklistData[$field->key][0]['value']) : null }}
                                    @elseif($field->type == 'select')
                                        {{ @$checklistData[$field->key][0]['value'] }}
                                    @elseif($field->type == 'radio')
                                        {{ @$checklistData[$field->key][0]['value'] }}
                                    @else($field->type == 'checkbox')
                                        {{ @$checklistData[$field->key][0]['value'] ? join(', ', json_decode($checklistData[$field->key][0]['value'])) : null }}
                                    @endif

                                    @if($field->description)
                                        <br>
                                        <small>{{ $field->description }}</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="2">Redatto da: {{ Str::title($r->username) }}</td>
                        <td class="text-right">Creato il {{ dataOra($r->created_at) }}</td>
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
                    <b>Checklist: {{ $r->tpl->label }}</b>
                </span>
            </td>
            <td class="text-right">
                <span class="font-size-6 text-uppercase">Stampa checklist</span>
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
