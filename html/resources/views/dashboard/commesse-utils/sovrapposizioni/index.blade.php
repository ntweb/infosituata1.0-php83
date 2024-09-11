@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Analisi sovrapposizione fasi', 'icon' => 'bx bx-outline', 'right_component' => 'dashboard.commesse-utils.sovrapposizioni.components.index-header'])
        Sovrapposizioni
    @endcomponent
@endsection

@section('content')
    <form class="ns-html" method="POST" data-route="{{ route('commessa-utils.sovrapposizioni-gantt') }}" data-container="#gantt20" data-callback="setTimeout(function() {drawGanttLines();}, 1000);">
        @csrf

        <div class="row align-items-end">
            @component('layouts.components.forms.select2-fasi', ['name' => 'fase', 'value' => null, 'class' => 'col-md-2'])
                Fase
            @endcomponent

                @component('layouts.components.forms.date-native', ['name' => 'start',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                    Data inizio prev
                @endcomponent

                @component('layouts.components.forms.date-native', ['name' => 'end',  'value' => \Carbon\Carbon::now()->toDateString(), 'class' => 'col-md-3'])
                    Data fine prev.
                @endcomponent

            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" class="btn btn-primary mb-1 btnCheckSovrapposizioni">Controlla</button>
                </div>
            </div>
        </div>
    </form>

    <div id="gantt20" class="table-responsive" style="flex: 1 1 auto;"></div>

@endsection
