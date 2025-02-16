@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei topic', 'icon' => 'bx bx-chat', 'right_component' => 'dashboard.topic.components.index-header'])
        Topic
    @endcomponent
@endsection

@section('modal')
    @include('dashboard.topic.modals.index-topic-search')
@endsection

@section('content')

    <div class="app-main__inner p-0">
        <div class="app-inner-layout">
            <div class="app-inner-layout__wrapper">
                <div class="app-inner-layout__content card">
                    <div>
                        <div class="bg-white">
                            @if(count($list))
                            <div class="table-responsive">
                                <table class="text-nowrap table-lg mb-0 table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th>Creato da</th>
                                            <th class="text-right">Data ora</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $el)
                                        @php
                                            $_is_opened = isOpenedMessage($el);
                                        @endphp
                                        <tr class="{{ !$_is_opened ? 'bold' : null }}">
                                            <td>{{ Str::title(Str::limit($el->oggetto, '30')) }}</td>
                                            <td>{{ Str::title($el->user->name) }}</td>
                                            <td class="text-right">
                                                {{ dataOra($el->created_at) }}
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('topic.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Leggi</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="m-2">
                                    @component('layouts.components.alerts.warning')
                                        Nessun elemento trovato
                                    @endcomponent
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
