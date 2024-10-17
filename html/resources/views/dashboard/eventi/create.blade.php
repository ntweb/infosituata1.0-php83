@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
        if (isset($el)) {
            $back = route('evento.index');
        }
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-calendar-event', 'back' => $back])
        Evento
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.eventi.forms.create')
        </div>

        @if(isset($el))
            @if(!$el->timbrature_permessi_id)
            <div class="col-md-12 col-lg-4">
                @component('layouts.components.helpers.delete-evento', ['el' => $el, 'redirect' => route('evento.index')])
                @endcomponent
            </div>
            @endif
        @endif

    </div>

@endsection
