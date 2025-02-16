@extends('layouts.dashboard')
@section('header')

    @component('layouts.components.header', ['subtitle' => 'Benvenuto nella dashboard', 'icon' => 'pe-7s-portfolio', 'right_component' => 'dashboard.home.components.index-header'])
        Dashboard
    @endcomponent

@endsection

@section('content')

    <div class="row">
        <div class="{{ count($tasks) ? 'col-md-8' : 'col-12' }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @include('dashboard.scadenzario.tables.index')
                </div>
                @if(count($messages))
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-header">Comunicazioni</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Oggetto</th>
                                        <th>Inviato da</th>
                                        <th>Inviato il</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>{{ @Str::limit($message->messaggio->oggetto, 30) }}</td>
                                            <td>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <img width="40" class="rounded-circle" src="{{ url('assets/images/avatars/1.jpg') }}" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left flex2">
                                                            <div class="widget-heading">{{ @$message->messaggio->user->name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ dataOra($message->created_at) }}
                                            </td>
                                            <td class="text-right">
                                                @if(@$message->messaggio->priority == 'important')
                                                    @component('layouts.components.labels.warning')
                                                        importante
                                                    @endcomponent
                                                @endif
                                                @if($message->opened_at)
                                                    @component('layouts.components.labels.success')
                                                        letto
                                                    @endcomponent
                                                @else
                                                    @component('layouts.components.labels.error')
                                                        non letto
                                                    @endcomponent
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('messaggio.show-user', [@$message->messaggio->id]) }}" class="btn btn-primary btn-sm">Apri</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if(count($tasks))
        <div class="col-md-4">
            @include('dashboard.tasks.assegnati-dashboard')
        </div>
        @endif
    </div>
@endsection
