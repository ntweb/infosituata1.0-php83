@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-portfolio', 'back' => isset($el) ? route('package.index') : null])
        Package
    @endcomponent
@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.package.forms.create')
        </div>

        <div class="col-md-12 col-lg-4">

        </div>

    </div>

@endsection
