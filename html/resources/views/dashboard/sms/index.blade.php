@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei sms', 'icon' => 'bx bx-message-alt-detail', 'right_component' => 'dashboard.sms.components.index-header'])
        SMS
    @endcomponent
@endsection

@section('content')

    <div class="app-main__inner p-0">
        <div class="app-inner-layout">
            <div class="app-inner-layout__wrapper">
                <div class="app-inner-layout__content card">
                    <div>
                        <div class="app-inner-layout__top-pane">
                            <div class="pane-left">
                                <div class="mobile-app-menu-btn">
                                    <button type="button" class="hamburger hamburger--elastic">
                                        <span class="hamburger-box">
                                            <span class="hamburger-inner"></span>
                                        </span>
                                    </button>
                                </div>
                                <h4 class="mb-0">{{ $title }}</h4>
                            </div>
                        </div>
                        <div class="bg-white">
                            @if(count($list))
                            <div class="table-responsive">
                                <table class="text-nowrap table-lg mb-0 table table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <i class="fa fa-star"></i>
                                            </th>
                                            <th>Oggetto</th>
                                            <th>Mittente</th>
                                            <th class="text-right">Data ora</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $el)
                                        @php
                                            $_is_opened = isOpenedMessage($el);
                                        @endphp
                                        <tr class="{{ !$_is_opened ? 'bold' : null }}">
                                            <td>
                                                @if($el->priority == 'important')
                                                    @component('layouts.components.labels.warning')
                                                        importante
                                                    @endcomponent
                                                @endif
                                            </td>
                                            <td>{{ Str::title(Str::limit($el->oggetto, '30')) }}</td>
                                            <td>{{ Str::title($el->user->name) }}</td>
                                            <td class="text-right">
                                                @if($el->exception)
                                                    @if($el->sent_at)
                                                        {{ dataOra($el->sent_at) }}&nbsp;
                                                    @endif
                                                        @component('layouts.components.labels.error')
                                                            errore
                                                        @endcomponent
                                                @else
                                                    @if($el->sent_at)
                                                        {{ dataOra($el->sent_at) }}&nbsp;
                                                        @component('layouts.components.labels.success')
                                                            inviato
                                                        @endcomponent
                                                    @else
                                                        @component('layouts.components.labels.warning')
                                                            bozza
                                                        @endcomponent
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $el->exception }}</td>
                                            <td class="text-right">
                                                @can('can-create')
                                                    <a href="{{ route('sms.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                                @endcan
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
                <div class="app-inner-layout__sidebar card">
                    <ul class="nav flex-column">
                        @can('can-create')
                        <li class="pt-4 pl-3 pr-3 pb-3 nav-item">
                            <a href="{{ route('sms.create') }}" class="btn-pill btn-shadow btn btn-primary btn-block">Nuovo sms</a>
                        </li>
                        @endcan
                        <li class="nav-item-header nav-item">Il mio account</li>
                        <li class="nav-item">
                            <a href="{{ route('sms.index', ['fl' => 'inbox']) }}" class="nav-link">
                                <i class="nav-link-icon pe-7s-chat"> </i><span>Ricevuti</span>
{{--                                <div class="ml-auto badge badge-pill badge-info">8</div>--}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sms.index', ['fl' => 'sent']) }}" class="nav-link">
                                <i class="nav-link-icon pe-7s-wallet"> </i><span>Inviati</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
