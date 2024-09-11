@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-portfolio', 'right_component' => isset($el) ? 'dashboard.azienda.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('azienda.index') : null])
        Azienda
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.azienda.forms.create')
            @if(isset($el))
                @include('dashboard.azienda.forms.fatturazione')
            @endif
        </div>

        <div class="col-md-12 col-lg-4">
            @if(isset($el))
                @include('dashboard.azienda.forms.credenziali')
                @include('dashboard.azienda.forms.package')
            @endif
        </div>

    </div>

@endsection
