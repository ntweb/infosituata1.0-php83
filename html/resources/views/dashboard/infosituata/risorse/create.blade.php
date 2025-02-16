@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuova', 'icon' => 'pe-7s-home', 'back' => $back])
        Risorsa
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.infosituata.risorse.forms.create')

            @if(isset($el))
                @include('dashboard.infosituata.risorse.forms.risorsa-esterna')
                @include('dashboard.infosituata.risorse.forms.risorsa-interna')
            @endif
        </div>

        <div class="col-md-12 col-lg-4">
            @if(isset($el))
                @component('layouts.components.helpers.infosituata', ['el' => $el])
                @endcomponent

                @component('layouts.components.helpers.delete', ['el' => $el, 'redirect' => route('risorse.index')])
                @endcomponent
            @endif
        </div>

    </div>

@endsection
