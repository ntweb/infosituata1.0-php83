@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        Modulo Mancato infortunio
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-lg-7">
            @include('layouts.components.alerts.alert')
            @include('dashboard.inail.modot23.forms.create')
        </div>
        <div class="col-lg-5">
            @if(isset($el))
                @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'inail_modot23'])
                    Mancato infortunio
                @endcomponent
            @endif
        </div>

    </div>

@endsection
