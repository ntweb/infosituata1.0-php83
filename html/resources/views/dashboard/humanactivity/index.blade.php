@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista H.A.', 'icon' => 'pe-7s-menu', 'right_component' => null])
        Human activity
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('human-activity.index' ) }}?q=" value="{{ request()->get('q') }}">
            <button class="search-icon"><span></span></button>
        </div>
        <button class="close"></button>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($today))
                @component('dashboard.humanactivity.components.index-table', ['list' => $today, 'today' => true])
                @endcomponent
            @endif

            @if(count($today) && count($list))
            <hr>
            @endif

            @if(count($list))
                @component('dashboard.humanactivity.components.index-table', ['list' => $list, 'today' => false])
                @endcomponent
            @else
                @component('layouts.components.alerts.warning')
                    Nessun elemento trovato
                @endcomponent
            @endif

        </div>
    </div>

@endsection

@section('modal')
    @include('dashboard.humanactivity.components.modal-detail')
@endsection
