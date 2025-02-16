@extends('layouts.dashboard')
@section('header')
    @php
        if(!isset($back))
            $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $item->extras1, 'icon' => 'pe-7s-home', 'back' => $back, 'el' => $el, 'right_component' => 'dashboard.controllo.components.edit-header'])
        Modifica controllo
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5">
            @include('layouts.components.alerts.alert')
            @include('dashboard.controllo.forms.create')
            @include('dashboard.controllo.forms.delete')
        </div>
        <div class="col-md-7">
            <div id="dettagli">
            </div>
            <div id="form-create"></div>
        </div>
    </div>
@endsection
