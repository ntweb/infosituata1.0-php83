@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Errore', 'icon' => 'pe-7s-menu'])
        Package error
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            @component('layouts.components.alerts.error')
                {!! $error !!}
            @endcomponent
        </div>
    </div>

@endsection
