@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Lista timbrature giornata', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => 'dashboard.timbrature.components.index-header', 'date' => $date, 'list' => $list])
        Timbrature
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Timbrature del {{ $date }}</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover" id="dashboard_timbrature_index">
                                <thead>
                                <tr>
                                    <th>Utente</th>
                                    <th>Giorno</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $k => $timbrature)
                                    @php
                                        $name = explode('#', $k);
                                    @endphp
                                    <tr>
                                        <td>{{ Str::title($name[1]) }}</td>
                                        <td>{{ $date }}</td>
                                        <td>
                                            @foreach($timbrature as $el)
                                                @component('layouts.components.timbratura.ora-verso', ['timbratura' => $el])
                                                @endcomponent
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @can('can-create')
                                                <a href="{{ route('timbrature.edit', [$name[0], 'date' => $date]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessuna timbratura effettuata
                @endcomponent
            @endif
        </div>
    </div>
@endsection
