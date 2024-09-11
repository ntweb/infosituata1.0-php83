@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back])
        Materiale
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-8">
            @include('layouts.components.alerts.alert')
            @include('dashboard.materiali.forms.create')
            @if(isset($el))
                @include('dashboard.item.forms.sedi')

                @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'items'])
                    Materiale
                @endcomponent

                @include('dashboard.materiali.forms.bigtext')
            @endif
        </div>
        <div class="col-md-12 col-lg-4">
            @if(isset($el))
                @component('layouts.components.helpers.infosituata', ['el' => $el])
                @endcomponent

                @component('layouts.components.helpers.delete', ['el' => $el, 'redirect' => route('materiali.index')])
                @endcomponent
            @endif
        </div>
    </div>
@endsection
