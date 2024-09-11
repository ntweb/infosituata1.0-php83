@extends('layouts.dashboard')
@section('header')
    @php
        if(!isset($back))
            $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $item->extras1, 'icon' => 'fas fa-gas-pump', 'back' => $back, 'el' => $el, 'right_component' => 'dashboard.carburante.components.edit-header'])
        Modifica controllo
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5">
            @include('layouts.components.alerts.alert')
            @include('dashboard.carburante.forms.create')

            @if(!$nextScheda)
                @include('dashboard.carburante.forms.delete')
            @endif
        </div>
        <div class="col-md-7">
            <div id="dettagli">
            </div>
            <div id="form-create"></div>
        </div>
    </div>
@endsection
