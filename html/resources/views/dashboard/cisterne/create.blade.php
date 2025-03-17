@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => isset($el) ? 'dashboard.cisterne.components.edit-header' : null, 'el' => isset($el) ? $el : null])
        Cisterna
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-7">
            @include('layouts.components.alerts.alert')
            @include('dashboard.cisterne.forms.create')
            @include('dashboard.cisterne.forms.create-carico')
        </div>
        @if(isset($schedeCarburante))
        <div class="col-md-12 col-lg-5">
            <div class="main-card card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="cisterne-log-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                Carichi carburante
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="schede-carburante-log-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                Ultime schede inserite
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="cisterne-log-tab">
                            @include('dashboard.cisterne.components.carichi-logs')
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="schede-carburante-log-tab">
                            @include('dashboard.cisterne.components.logs')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
