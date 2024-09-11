@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Scheduler commesse', 'icon' => 'bx bx-calendar-check', 'right_component' => 'dashboard.commesse-utils.scheduler-commesse.components.index-header'])
        Scheduler commesse
    @endcomponent
@endsection

@section('content')

    <div class="d-flex align-items-center justify-content-between">
        <div class="w-100">
            <form class="ns-html" method="POST" data-route="{{ route('commessa-utils.show-scheduler-commesse') }}" data-container="#gantt20" data-callback="setTimeout(function() {drawGanttLines();}, 1000);">
                @csrf
                <div class="row align-items-end">

                    @component('layouts.components.forms.date-native', ['name' => 'start',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                        Data inizio
                    @endcomponent

                    @component('layouts.components.forms.date-native', ['name' => 'end',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                        Data fine
                    @endcomponent

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary mb-1 btnShowScheduleCommesse">Controlla</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div style="min-width: 250px">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" id="showHideCommessaDetailsCheckbox" class="custom-control-input">
                <label class="custom-control-label" for="showHideCommessaDetailsCheckbox">
                    Mostra dettagli
                </label>
            </div>
        </div>
    </div>


    <div id="gantt20" class="table-responsive" style="flex: 1 1 auto;"></div>
@endsection
