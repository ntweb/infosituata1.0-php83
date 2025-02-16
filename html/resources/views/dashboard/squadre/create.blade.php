@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuova', 'icon' => 'bx bxs-user-detail', 'right_component' => isset($el) ? 'dashboard.commesse-tpl.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('squadra.index') : null])
        Squadra
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.squadre.forms.create')
            @if(isset($el))
                @component('dashboard.squadre.components.delete', ['el' => $el, 'redirect' => route('squadra.index')])
                @endcomponent
            @endif
        </div>

        @if(isset($el))
        <div class="col-md-8">
            @include('dashboard.squadre.forms.items')
        </div>
        @endif

    </div>

@endsection
