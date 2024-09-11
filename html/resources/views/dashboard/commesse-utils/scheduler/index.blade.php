@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Scheduler risorse', 'icon' => 'bx bx-calendar-check', 'right_component' => 'dashboard.commesse-utils.scheduler.components.index-header'])
        Scheduler
    @endcomponent
@endsection

@section('content')
    <form class="ns-html" method="POST" data-route="{{ route('commessa-utils.show-scheduler') }}" data-container="#scheduler-container" data-callback="drawLines()">
        @csrf

        <div class="row align-items-end">

{{--            @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => 'Periodo',  'start' => \Carbon\Carbon::now()->toDateString(), 'end' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])--}}
{{--            @endcomponent--}}

            @component('layouts.components.forms.date-native', ['name' => 'start',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                Data inizio
            @endcomponent

            @component('layouts.components.forms.date-native', ['name' => 'end',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                Data fine
            @endcomponent

            <div class="col-md-3">
                <div class="form-group">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="javascript:void(0)" class="btn btn-light mb-1 addItemSearch" data-route="{{ route('commessa-utils.scheduler-select-items', ['_module' => 'utente']) }}">Utenti</a>
                        <a href="javascript:void(0)" class="btn btn-light mb-1 addItemSearch" data-route="{{ route('commessa-utils.scheduler-select-items', ['_module' => 'mezzo']) }}">Mezzi</a>
                        <a href="javascript:void(0)" class="btn btn-light mb-1 addItemSearch" data-route="{{ route('commessa-utils.scheduler-select-items', ['_module' => 'attrezzatura']) }}">Attrezzature</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" class="btn btn-primary mb-1 btnShowSchedule">Controlla</button>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div id="exampleAccordion" data-children=".item">
                    <div class="item">
                        <a aria-expanded="false" aria-controls="exampleAccordion1" data-toggle="collapse" href="#collapseExample" class="m-0 p-0 btn btn-link collapsed" style="text-decoration: none">Elementi selezionati</a>
                        <div data-parent="#exampleAccordion" id="collapseExample" class="collapse" style="">
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <p class="lead d-flex align-items-center">
                                        <i class="bx bx-user mr-1"></i> <span>Utenti</span>
                                    </p>
                                    <hr>
                                    <ul id="ul-utente" class="no-bullets">
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <p class="lead d-flex align-items-center">
                                        <i class="bx bxs-car-mechanic mr-1"></i> <span>Mezzi</span>
                                    </p>
                                    <hr>
                                    <ul id="ul-mezzo" class="no-bullets">
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <p class="lead d-flex align-items-center">
                                        <i class="bx bx-wrench mr-1"></i> <span>Attrezzature</span>
                                    </p>
                                    <hr>
                                    <ul id="ul-attrezzatura" class="no-bullets">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </form>
    <div id="scheduler-container" style="max-width: 500px"></div>
@endsection
