@extends('pdf.commessa.base')

@section('style')
    <style type="text/css">
        header { border-bottom: solid 0.2mm #000 }
        footer { border-top: solid 0.2mm #000 }
    </style>
@endsection

@section('content')

    @include('pdf.commessa.frontpage')

    @if(request()->has('dettaglio_fasi'))
        <div class="page-break"></div>
        @include('pdf.commessa.fasi')
    @endif

    @if(request()->has('dettaglio_fasi'))
        <div class="page-break"></div>
        @include('pdf.commessa.risorse')
    @endif

    @if(request()->has('log_risorse'))
        <div class="page-break"></div>
        @include('pdf.commessa.logs')
    @endif

    @if(request()->has('rapportini'))
        <div class="page-break"></div>
        @include('pdf.commessa.rapportini')
    @endif

    @if(request()->has('checklist'))
        <div class="page-break"></div>
        @include('pdf.commessa.checklist')
    @endif

    @if(request()->has('allegati'))
        <div class="page-break"></div>
        @include('pdf.commessa.allegati')
    @endif

@endsection

