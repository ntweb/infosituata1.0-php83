@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header')
        Modulo non attivo
    @endcomponent
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('layouts.components.alerts.warning')
                {{ $message }}
                @if($message_2)
                    <br>
                    <br>
                    <em>{!! $message_2 !!}</em>
                @endif
            @endcomponent
        </div>
    </div>
@endsection
