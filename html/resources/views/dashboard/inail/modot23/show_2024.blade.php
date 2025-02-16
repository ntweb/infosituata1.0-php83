@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Modulo compilato', 'icon' => 'pe-7s-home', 'back' => $back])
        Modulo Mancato infortunio
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">

            @include('dashboard.inail.modot23.forms.create_2024')

        </div>

        @if(isset($el))
        <div class="col-lg-4">
            @component('dashboard.upload.component.modot-upload-file', ['item' => $el])
                Allegati
            @endcomponent
        </div>
        @endif

    </div>

@endsection
