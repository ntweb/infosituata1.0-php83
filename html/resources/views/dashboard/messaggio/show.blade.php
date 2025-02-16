@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $el->oggetto, 'icon' => 'pe-7s-home', 'back' => $back])
        Messaggio
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-6 col-lg-8">
            <div class="main-card mb-3 card">
                <div class="card-header">{{ $el->oggetto }}</div>
                <div class="card-body">
                    {!! $el->messaggio !!}
                </div>
            </div>

            @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'messaggi', 'read_only' => true])
                Messaggio
            @endcomponent
        </div>

        <div class="col-md-12 col-lg-4">
            @if(count($gruppiSel))
            <div class="main-card mb-3 card">
                <div class="card-header">Lista di distribuzione | Gruppi</div>
                <div class="card-body">
                        <ul class="list-group">
                            @foreach($gruppiSel as $el)
                            <li class="list-group-item">{{ $el->label }}</li>
                            @endforeach
                        </ul>
                </div>
            </div>

            @endif

            @if(count($utentiSel))
                <div class="main-card mb-3 card">
                    <div class="card-header">Lista di distribuzione | Utenti</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($utentiSel as $el)
                                @php
                                    $_opened = @$utentiSelOpened[$el->id];
                                @endphp
                                <li class="list-group-item">
                                    {{ Str::title($el->extras1.' '.$el->extras2) }}
                                    <span class="pull-right @if($_opened) text-success @endif"><i class="fa fa-check"></i></span>
                                    @if($_opened)
                                    <span class="pull-right mr-2">{{ dataOra($_opened) }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

    </div>

@endsection
