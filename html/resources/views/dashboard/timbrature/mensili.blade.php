@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Lista timbrature mese', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => 'dashboard.timbrature.components.mensili-header', 'date' => $date, 'list' => $list])
        Timbrature mensili
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        @php
                            $d = new \Carbon\Carbon($date.'-01');
                        @endphp
                        <h5 class="card-title">Timbrature del {{ $d->format('m-Y') }}</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-sm table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    @foreach($period as $p)
                                        <th class="text-center">{{ $p->format('d') }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $users_id => $name)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::title($name) }}</td>
                                            @foreach($period as $p)
                                                <td class="text-center">
                                                    @php
                                                        $d = $p->toDateString();
                                                        $result = @$listChecked[$users_id][$d];
                                                    @endphp

                                                    <a href="{{ route('timbrature.edit', [$users_id, 'date' => $d]) }}">

                                                        @if(!isset($result))
                                                            <span
                                                                class="text-danger"
                                                                data-toggle="tooltip"
                                                                data-placement="left"
                                                                data-title="Timbrature assenti">
                                                            -
                                                        </span>
                                                        @else
                                                            @if (is_numeric($result))
                                                                <span
                                                                    class="font-weight-bold"
                                                                    data-toggle="tooltip"
                                                                    data-placement="left"
                                                                    data-title="Ore lavorate">
                                                                {{ $result }}
                                                            </span>
                                                            @else
                                                                <span
                                                                    class="text-warning"
                                                                    data-toggle="tooltip"
                                                                    data-placement="left"
                                                                    data-title="{{ $result }}">
                                                                <i class="bx bxs-calendar-exclamation" ></i>
                                                            </span>
                                                            @endif
                                                        @endif

                                                    </a>
                                                </td>
                                            @endforeach
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
