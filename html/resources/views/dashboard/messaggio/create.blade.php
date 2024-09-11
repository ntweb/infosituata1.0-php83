@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        Messaggio
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-7">
            @include('layouts.components.alerts.alert')
            @include('dashboard.messaggio.forms.create')
        </div>

        @if(isset($el))
            <div class="col-md-5">
                @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'messaggi'])
                    Messaggio
                @endcomponent
            </div>
        @endif

    </div>

@endsection

@section('modal')
    @include('dashboard.messaggio.components.modal-delete-attachment')
@endsection

