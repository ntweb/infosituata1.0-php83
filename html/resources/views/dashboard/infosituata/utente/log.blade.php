@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Infosituata', 'icon' => 'pe-7s-home', 'back' => $back])
        {{ Str::title($el->extras1.' '.$el->extras2) }}
    @endcomponent

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($logs))
            <a class="btn btn-success mb-2" href="{{ route('infosituata.export', [$el->id]) }}">
                <i class="icon ion-android-download"></i> Esporta in excel
            </a>
            @endif

            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title">
{{--                        <i class="header-icon pe-7s-date icon-gradient bg-love-kiss"> </i>--}}
                    </div>
                    <ul class="nav">
                        <li class="nav-item"><a data-toggle="tab" href="#tab-eg5-log" class="active nav-link">Log</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-eg5-log" role="tabpanel">
                            @if(count($logs))
                                <div class="table-responsive">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                    <tr>
                                        <th class="no-border-top">Item</th>
                                        <th class="no-border-top"></th>
                                        <th class="no-border-top"><i class="fa fa-calendar"></i> Data presa visione</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ Str::title($log->risorsa->label) }}</td>
                                            <td>{{ $log->risorsa->controller }}</td>
                                            <td>{{ dataOra($log->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                @component('layouts.components.alerts.warning')
                                    Nessun log registrato
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
