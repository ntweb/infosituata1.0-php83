<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <link rel="icon" href="{{ url('favicon.png') }}" type="image/png" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    {{--<link rel="stylesheet" href="{{ url('assets/js/vendors/summernote/dist/summernote.min.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ url('assets/js/vendors/summernote/dist/summernote-bs4.min.css') }}">--}}
    <link rel="stylesheet" href="{{ url('assets/css/base.css') }}?v=1.1">
    <link rel="stylesheet" href="{{ url('assets/css/styles.css') }}?v=2.2">
    <link rel="stylesheet" href="{{ url('assets/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/tags-input/dist/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/vendors/select2-4.0.13/dist/css/select2.min.css') }}">

    {{-- Datatable --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.css"/>

    {{-- Date range--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link href='{{ url('assets/boxicons-2.1.4/css/boxicons.min.css') }}' rel='stylesheet'>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="{{ url('assets/leaflet/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/leaflet.markercluster/dist/MarkerCluster.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/leaflet.markercluster/dist/MarkerCluster.Default.css') }}" />

    {{-- Dropzone --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}
    <link rel="stylesheet" href="{{ url('js/dropzone/dropzone.min.css') }}" type="text/css" />


    <link rel="stylesheet" href="{{ url('assets/css/scheduler-table.css') }}?v={{ time() }}">


    <style>
        body {
            overflow-x: hidden;
        }
        .fc-toolbar { text-transform: capitalize; }
        tr.bold { background-color: #f7f7f7; }
        tr.bold td { font-weight: bold; }
        .pb-10 { padding-bottom: 120px !important }
        .app-drawer-wrapper { max-width: 300px; }
		.note-toolbar.card-header { display: block; height: 8rem !important; }
        /*.select2-container--bootstrap4 .select2-selection { border: none;}*/
        .btn-link:hover { text-decoration: none !important; }
        .has-error-checkboxes { border: solid 1px #d92550; border-radius: 4px; }
    </style>

    <style type="text/css">
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-button {
            width: 0px;
            height: 0px;
        }
        ::-webkit-scrollbar-thumb {
            background: #919191;
            border: 0px none #ffffff;
            border-radius: 50px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #919191;
        }
        ::-webkit-scrollbar-thumb:active {
            background: #c7c7c7;
        }
        ::-webkit-scrollbar-track {
            background: #eeeeee;
            border: 0px none #ffffff;
            border-radius: 50px;
        }
        ::-webkit-scrollbar-corner {
            background: transparent;
        }
        .select2-selection {
            min-height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            min-height: 38px;
        }
        .table td.fit,
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            margin-bottom: calc(calc(2.25rem + 2px)/5 - 1px);
        }
        .datepicker-container {
            z-index: 5000 !important; /* has to be larger than 1050 */
        }
        table.dataTable.table-sm .sorting:before, table.dataTable.table-sm .sorting_asc:before, table.dataTable.table-sm .sorting_desc:before {
            top: 21px;
            right: 1rem;
        }
        table.dataTable.table-sm .sorting:after, table.dataTable.table-sm .sorting_asc:after, table.dataTable.table-sm .sorting_desc:after {
            top: 21px;
        }
        .pointer { cursor: pointer }
        ul.no-bullets {
            list-style-type: none; /* Remove bullets */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margins */
        }
        .td-saturday {
            background-color: #ffe290;
            color: #995601;
            font-weight: bold;
        }
        .td-sunday {
            background-color: #ffb0b0;
            color: #990101;
            font-weight: bold;
        }
        .table-responsive { -webkit-overflow-scrolling: auto; }

        .is-invalid + .select2-container--bootstrap .select2-selection--single {
            border: 1px solid #f44336;
        }
    </style>

    @php
        $azienda = getAziendaBySessionUser();
    @endphp
</head>

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar" id="print-screen">
    <!--Header START-->
    <div class="app-header header-shadow">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
        <div class="app-header__content">
            <div class="app-header-left">
                @yield('search')
                <ul class="header-megamenu nav">
{{--                    <li class="btn-group nav-item">--}}
{{--                        <a  class="nav-link" data-toggle="dropdown" aria-expanded="false">--}}
{{--                            <span class="badge badge-pill badge-danger ml-0 mr-2">4</span>--}}
{{--                            Settings--}}
{{--                            <i class="fa fa-angle-down ml-2 opacity-5"></i>--}}
{{--                        </a>--}}
{{--                        <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu">--}}
{{--                            <div class="dropdown-menu-header">--}}
{{--                                <div class="dropdown-menu-header-inner bg-secondary">--}}
{{--                                    <div class="menu-header-image opacity-5" style="background-image: url('../assets/images/dropdown-header/abstract2.jpg');"></div>--}}
{{--                                    <div class="menu-header-content">--}}
{{--                                        <h5 class="menu-header-title">Overview</h5>--}}
{{--                                        <h6 class="menu-header-subtitle">Dropdown menus for everyone</h6>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="scroll-area-xs">--}}
{{--                                <div class="scrollbar-container">--}}
{{--                                    <h6 tabindex="-1" class="dropdown-header">Key Figures</h6>--}}
{{--                                    <button type="button" tabindex="0" class="dropdown-item">Service Calendar</button>--}}
{{--                                    <button type="button" tabindex="0" class="dropdown-item">Knowledge Base</button>--}}
{{--                                    <button type="button" tabindex="0" class="dropdown-item">Accounts</button>--}}
{{--                                    <div tabindex="-1" class="dropdown-divider"></div>--}}
{{--                                    <button type="button" tabindex="0" class="dropdown-item">Products</button>--}}
{{--                                    <button type="button" tabindex="0" class="dropdown-item">Rollup Queries</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <ul class="nav flex-column">--}}
{{--                                <li class="nav-item-divider nav-item"></li>--}}
{{--                                <li class="nav-item-btn nav-item">--}}
{{--                                    <button class="btn-wide btn-shadow btn btn-danger btn-sm">Cancel</button>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <li class="dropdown nav-item">--}}
{{--                        <a aria-haspopup="true"  data-toggle="dropdown" class="nav-link" aria-expanded="false">--}}
{{--                            <i class="nav-link-icon pe-7s-settings"></i>--}}
{{--                            Projects--}}
{{--                            <i class="fa fa-angle-down ml-2 opacity-5"></i>--}}
{{--                        </a>--}}
{{--                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-rounded dropdown-menu-lg rm-pointers dropdown-menu">--}}
{{--                            <div class="dropdown-menu-header">--}}
{{--                                <div class="dropdown-menu-header-inner bg-success">--}}
{{--                                    <div class="menu-header-image opacity-1" style="background-image: url('../assets/images/dropdown-header/abstract3.jpg');"></div>--}}
{{--                                    <div class="menu-header-content text-left">--}}
{{--                                        <h5 class="menu-header-title">Overview</h5>--}}
{{--                                        <h6 class="menu-header-subtitle">Unlimited options</h6>--}}
{{--                                        <div class="menu-header-btn-pane">--}}
{{--                                            <button class="mr-2 btn btn-dark btn-sm">Settings</button>--}}
{{--                                            <button class="btn-icon btn-icon-only btn btn-warning btn-sm">--}}
{{--                                                <i class="pe-7s-config btn-icon-wrapper"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i>Graphic Design</button>--}}
{{--                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i>App Development</button>--}}
{{--                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i>Icon Design</button>--}}
{{--                            <div tabindex="-1" class="dropdown-divider"></div>--}}
{{--                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i>Miscellaneous</button>--}}
{{--                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i>Frontend Dev</button>--}}
{{--                        </div>--}}
{{--                    </li>--}}
                </ul>
            </div>
            <div class="app-header-right">
                <div class="header-dots">

                    @can('can-create')
                        @if ($azienda)
                            @if($uncheckedPermessi)
                            <div class="dropdown mr-2">
                                <a href="{{ route('timbrature-permessi.index', ['_only_in_attesa' => true]) }}" aria-haspopup="true" aria-expanded="false" class="p-0 btn btn-link dd-chart-btn">
                                    <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                        <span class="icon-wrapper-bg bg-warning"></span>
                                        <i class="icon text-warning pe-7s-clock icon-anim-pulse"></i>
                                        <span class="badge badge-dot badge-dot-sm badge-warning">Notifications</span>
                                    </span>
                                </a>
                            </div>
                            @endif
                        @endif
                    @endcan

                    @can('can-create')
                        @if ($azienda)
                            @php
                                $bg_class = "bg-success";
                                $text_class = "text-success";

                                if($azienda->module_sms_provider_credits <= 100) {
                                    $bg_class = "bg-warning";
                                    $text_class = "text-warning";
                                }

                                if($azienda->module_sms_provider_credits <= 50) {
                                    $bg_class = "bg-danger";
                                    $text_class = "text-danger";
                                }
                            @endphp
                            <div class="dropdown">
                                <button type="button" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false" class="p-0 btn btn-link dd-chart-btn">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg {{ $bg_class }}"></span>
                                    <i class="icon {{ $text_class }} bx bx-message-detail"></i>
                                </span>
                                </button>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-premium-dark">
                                            <div class="menu-header-content text-white">
                                                <h5 class="menu-header-title">SMS credits</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-chart">
                                        <div class="widget-chart-content">
                                            <div class="widget-numbers">
                                                <span>{{ $azienda->module_sms_provider_credits }}</span>
                                            </div>
                                        </div>
                                        <div class="widget-chart-wrapper">
                                            <div id="dashboard-sparkline-carousel-3-pop"></div>
                                        </div>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item-divider mt-0 nav-item"></li>
                                        <li class="nav-item-btn text-center nav-item">
                                            <a href="{{ route('sms.index') }}" class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                                Vai agli SMS
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    @endcan

                    {{-- Centro messaggi --}}
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                            <span class="icon-wrapper icon-wrapper-alt {{-- --}}rounded-circle">
                                <span class="icon-wrapper-bg @if($headerTopicsNumber || $headerMessagesNumber) bg-danger @endif"></span>
                                <i class="icon @if($headerTopicsNumber || $headerMessagesNumber) text-danger icon-anim-pulse @endif bx bx-chat"></i>
                                @if($headerTopicsNumber || $headerMessagesNumber)
                                    <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                                @endif
                            </span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu-header mb-0">
                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                    <div class="menu-header-image opacity-1" style="background-image: url({{ url('assets/images/dropdown-header/city3.jpg') }});"></div>
                                    <div class="menu-header-content text-dark">
                                        <h5 class="menu-header-title">Centro messaggi</h5>
                                    </div>
                                </div>
                            </div>
                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                <li class="nav-item">
                                    <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-topic-header">
                                        <span>Topic</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-messages-header">
                                        <span>Messaggi</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-topic-header" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div class="notifications-box">

                                                    @if(count($headerTopics))
                                                        <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                            @foreach($headerTopics as $m)
                                                                @if($m->topic)
                                                                <div class="vertical-timeline-item dot-primary vertical-timeline-element openUrl" style="cursor: pointer;" data-route="{{ route('topic.edit', [$m->messaggio_id]) }}">
                                                                    <div>
                                                                        <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                        <div class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">
                                                                                {{ Str::title($m->topic->oggetto) }}
                                                                            </h4>
                                                                            <span class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        Nessun topic da leggere
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-messages-header" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div class="notifications-box">
                                                    @if(count($headerMessages))
                                                        <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                            @foreach($headerMessages as $m)
                                                                <div class="vertical-timeline-item dot-primary vertical-timeline-element openUrl" style="cursor: pointer;" data-route="{{ route('messaggio.show-user', [$m->id]) }}">
                                                                    <div>
                                                                        <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                        <div class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">
                                                                                <b>{{ Str::title($m->user->name) }}</b>
                                                                                <span class="text-success">{{ dataOra($m->sent_at) }}</span>
                                                                                <br>
                                                                                {{ Str::title($m->oggetto) }}
                                                                            </h4>
                                                                            <span class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div>
                                                            Nessun messaggio da leggere
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                            <span class="icon-wrapper icon-wrapper-alt {{-- --}}rounded-circle">
                                <span class="icon-wrapper-bg @if($headerNotificationsNumber) bg-danger @endif"></span>
                                <i class="icon @if($headerNotificationsNumber) text-danger icon-anim-pulse @endif ion-android-notifications"></i>
                                @if($headerNotificationsNumber)
                                    <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                                @endif
                            </span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu-header mb-0">
                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                    <div class="menu-header-image opacity-1" style="background-image: url({{ url('assets/images/dropdown-header/city3.jpg') }});"></div>
                                    <div class="menu-header-content text-dark">
                                        <h5 class="menu-header-title">Centro notifiche</h5>
                                    </div>
                                </div>
                            </div>
                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                <li class="nav-item">
                                    <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-notifications-header">
                                        <span>Notifiche</span>
                                    </a>
                                </li>
                                @if(Auth::user()->utente_id <= 0  || Auth::user()->power_user)
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-events-header">
                                        <span>Stato sistema</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-notifications-header" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div class="notifications-box">
                                                    @if(count($headerNotifications))
                                                        <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                            @foreach($headerNotifications as $m)
                                                                <div class="vertical-timeline-item dot-primary vertical-timeline-element openUrl" style="cursor: pointer;" data-route="{{ route('notification.show', [$m->id]) }}">
                                                                    <div>
                                                                        <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                        <div class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">
                                                                                <b>{{ Str::title($m->module) }}</b>
                                                                                <br>
                                                                                <small>
                                                                                    @if(!$m->opened_at)
                                                                                        <strong><em>{{ Str::title($m->label) }}</em></strong>
                                                                                    @else
                                                                                        {{ Str::title($m->label) }}
                                                                                    @endif
                                                                                </small>
                                                                            </h4>
                                                                            <span class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div>
                                                            Nessun messaggio da leggere
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-events-header" role="tabpanel">
                                    @if(Auth::user()->utente_id <= 0 || Auth::user()->power_user)
                                        @php
                                            $_infos = currentCloudUsage();
                                        @endphp
                                        <div class="scroll-area-sm">
                                            <div class="scrollbar-container">
                                                <div class="p-3">
                                                    <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                        <div class="vertical-timeline-item vertical-timeline-element">
                                                            <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-{{ $_infos['status'] }}"> </i></span>
                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">Occupazione spazio cloud</h4>
                                                                    <p>{{ isa_convert_bytes_to_specified($_infos['size'], 'G') }} Gb / {{ @$_infos['package']->size ? isa_convert_bytes_to_specified($_infos['package']->size, 'G') : 0 }} Gb</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left header-user-info">
                                <div class="widget-heading">
                                    {{ Str::title(Auth::user()->name) }}
                                </div>
                                <div class="widget-subheading">
                                    @if(Auth::user()->superadmin)
                                        Superadmin
                                    @elseif (Auth::user()->utente_id)
                                        Utente
                                    @else
                                        Power user
                                    @endif
                                </div>
                            </div>
                            <div class="widget-content-left ml-3">
                                <div class="btn-group">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                        <img width="42" class="rounded-circle" src="{{ url('assets/images/avatars/1.jpg') }}" alt="">
                                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2" style="background-image: url({{ url('assets/images/dropdown-header/city3.jpg') }});"></div>
                                                <div class="menu-header-content text-left">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <img width="42" class="rounded-circle"
                                                                     src="{{ url('assets/images/avatars/1.jpg') }}"
                                                                     alt="">
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">{{ Auth::user()->name }}</div>
                                                                <div class="widget-subheading opacity-8">
                                                                    @if(Auth::user()->superadmin) Superadmin @endif
                                                                    @if(Auth::user()->azienda_id) Azienda @endif
                                                                    @if(Auth::user()->utente_id) Utente @endif
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right mr-2">
                                                                <form action="{{ route('logout') }}" id="frmLogout" method="post">@csrf</form>
                                                                <button class="btn-pill btn-shadow btn-shine btn btn-focus btnLogout">Logout</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="scroll-area-xs" style="height: 150px;">--}}
{{--                                            <div class="scrollbar-container ps">--}}
{{--                                                <ul class="nav flex-column">--}}
{{--                                                    <li class="nav-item-header nav-item">Activity--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="javascript:void(0);" class="nav-link">Chat--}}
{{--                                                            <div class="ml-auto badge badge-pill badge-info">8--}}
{{--                                                            </div>--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="javascript:void(0);" class="nav-link">Recover Password--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item-header nav-item">My Account--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="javascript:void(0);" class="nav-link">Settings--}}
{{--                                                            <div class="ml-auto badge badge-success">New--}}
{{--                                                            </div>--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="javascript:void(0);" class="nav-link">Messages--}}
{{--                                                            <div class="ml-auto badge badge-warning">512--}}
{{--                                                            </div>--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="javascript:void(0);" class="nav-link">Logs--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <ul class="nav flex-column">--}}
{{--                                            <li class="nav-item-divider mb-0 nav-item"></li>--}}
{{--                                        </ul>--}}
{{--                                        <div class="grid-menu grid-menu-2col">--}}
{{--                                            <div class="no-gutters row">--}}
{{--                                                <div class="col-sm-6">--}}
{{--                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">--}}
{{--                                                        <i class="pe-7s-chat icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i>--}}
{{--                                                        Message Inbox--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-sm-6">--}}
{{--                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">--}}
{{--                                                        <i class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>--}}
{{--                                                        <b>Support Tickets</b>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <ul class="nav flex-column">
{{--                                            <li class="nav-item-divider nav-item"></li>--}}
                                            <li class="nav-item-btn text-center nav-item">
                                                <a href="{{ route('user.password') }}" class="btn-wide btn btn-primary btn-sm">
                                                    Modifica password
                                                </a>
                                            </li>
                                        </ul>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!--Header END-->

    <div class="app-main">
        <div class="app-sidebar sidebar-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
            </div>

            @can('privacy-accepted')
            <div class="scrollbar-sidebar">
                <div class="app-sidebar__inner">
                    <ul class="vertical-nav-menu">
                        <li class="app-sidebar__heading">Menu</li>
                        <li class="{{ isActive('dashboard.*') }}">
                            <a href="{{ url('/home') }}">
                                <i class="metismenu-icon bx bx-rocket"></i> Dashboard
                            </a>
                        </li>
                        <li class="{{ isActive(['sede.*', 'gruppo.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-building"></i>
                                Azienda
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('sede.*') }}">
                                    <a href="{{ route('sede.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Sedi
                                    </a>
                                </li>
                                <li class="{{ isActive('gruppo.*') }}">
                                    <a href="{{ route('gruppo.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Gruppi
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive(['user.*', 'mezzi.*', 'attrezzature.*', 'scadenzario.*', 'risorse.*', 'materiali.*', 'evento.*', 'log-visualizzazioni.*', 'cisterne.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-map"></i>
                                Infosituata
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('user.*') }}">
                                    <a href="{{ route('user.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Utenti
                                    </a>
                                </li>
                                <li class="{{ isActive('mezzi.*') }}">
                                    <a href="{{ route('mezzi.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Mezzi
                                    </a>
                                </li>
                                <li class="{{ isActive('attrezzature.*') }}">
                                    <a href="{{ route('attrezzature.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Attrezzature
                                    </a>
                                </li>
                                <li class="{{ isActive('materiali.*') }}">
                                    <a href="{{ route('materiali.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Materiali
                                    </a>
                                </li>
                                <li class="{{ isActive('risorse.*') }}">
                                    <a href="{{ route('risorse.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Risorse
                                    </a>
                                </li>
                                @can('testing')
                                @can('can_create_mezzi')
                                    <li class="{{ isActive('cisterne.*') }}">
                                        <a href="{{ route('cisterne.index') }}">
                                            <i class="metismenu-icon pe-7s-diamond"></i>
                                            Cisterne
                                        </a>
                                    </li>
                                @endcan
                                @endcan
                                <li class="{{ isActive('scadenzario.*') }}">
                                    <a href="{{ route('scadenzario.index') }}">
                                        <i class="metismenu-icon pe-7s-diamond"></i>
                                        Scadenzario
                                    </a>
                                </li>
                                @can('can_create_tip_scadenza')
                                <li>
                                    <a href="{{ route('tipologia-scadenza.index') }}">
                                        <i class="metismenu-icon pe-7s-diamond"></i>
                                        Tipologia scadenza
                                    </a>
                                </li>
                                @endcan
                                <li class="{{ isActive('evento.*') }}">
                                    <a href="{{ route('evento.index') }}">
                                        <i class="metismenu-icon pe-7s-diamond"></i>
                                        Eventi
                                    </a>
                                </li>
                                @can('can_create_utenti')
                                <li class="{{ isActive('log-visualizzazioni.*') }}">
                                    <a href="{{ route('log-visualizzazioni.index') }}">
                                        <i class="metismenu-icon pe-7s-diamond"></i>
                                        Log visualizzazioni
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>

                        @if(!auth()->user()->superadmin)
                        <li class="{{ isActive(['timbrature.*', 'timbrature-permessi.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-time"></i>
                                Rilevazione presenze
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>

                                <li class="{{ isActive('timbrature.create') }}">
                                    <a href="{{ route('timbrature.create') }}">
                                        <i class="metismenu-icon"></i>
                                        Inserisci timbratura
                                    </a>
                                </li>

                                <li class="{{ isActive('timbrature-permessi.create') }}">
                                    <a href="{{ route('timbrature-permessi.index', ['_user' => true]) }}">
                                        <i class="metismenu-icon"></i>
                                        Permessi
                                    </a>
                                </li>

                                @can('can-list-timbrature-module')
                                <li class="{{ isActive(['timbrature.index', 'timbrature.mensili', 'timbrature-permessi.index']) }}">
                                    <a href="#">
                                        Ufficio personale
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li class="{{ isActive('timbrature.index') }}">
                                            <a href="{{ route('timbrature.index') }}">
                                                <i class="metismenu-icon"></i>
                                                Timbrature giornaliere
                                            </a>
                                        </li>
                                        <li class="{{ isActive('timbrature.mensili') }}">
                                            <a href="{{ route('timbrature.mensili') }}">
                                                <i class="metismenu-icon"></i>
                                                Timbrature mensili
                                            </a>
                                        </li>
                                        <li class="{{ isActive('timbrature-permessi.index') }}">
                                            <a href="{{ route('timbrature-permessi.index') }}">
                                                <i class="metismenu-icon"></i>
                                                Richieste permessi
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('timbrature.qr-generator') }}">
                                                <i class="metismenu-icon"></i>
                                                Qr acc. timbrature
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @endcan

                            </ul>
                        </li>
                        @endif

                        <li class="{{ isActive(['messaggio.*', 'sms.*', 'topic.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-comment-dots"></i>
                                Comunicazioni
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('messaggio.*') }}">
                                    <a href="{{ route('messaggio.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Messaggi
                                    </a>
                                </li>

                                <li class="{{ isActive('topic.*') }}">
                                    <a href="{{ route('topic.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Topic
                                    </a>
                                </li>

                                <li class="{{ isActive('sms.*') }}">
                                    <a href="{{ route('sms.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Sms
                                    </a>
                                </li>

                                @can('can_create_whatsapp')
                                    <li class="{{ isActive('whatsapp.*') }}">
                                        <a href="#">
                                            <i class="metismenu-icon"></i>
                                            Whatsapp
                                        </a>
                                        <ul>
                                            <li class="{{ isActive('whatsapp.index') }}"><a href="{{ route('whatsapp.index') }}">Lista dei broadcast</a></li>
                                            <li class="{{ isActive('whatsapp.create') }}"><a href="{{ route('whatsapp.create') }}">Nuovo broadcast</a></li>
                                            <li class="{{ isActive('whatsapp.chat') }}"><a href="{{ route('whatsapp.chat') }}">Chat</a></li>
                                        </ul>
                                    </li>
                                @endcan

                            </ul>
                        </li>

                        <li class="{{ isActive(['rapportini.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-edit-alt"></i>
                                Rapportini
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('rapportini.index') }}">
                                    <a href="{{ route('rapportini.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Elenco
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ isActive(['checklist.*', 'checklist-template.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-check-circle"></i>
                                Checklist
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('checklist.index') }}">
                                    <a href="{{ route('checklist.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Elenco
                                    </a>
                                </li>
                                @can('can_create_template_checklist')
                                <li class="{{ isActive('checklist-tpl.index') }}">
                                    <a href="{{ route('checklist-template.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Template
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>

                        <li class="{{ isActive(['task.*', 'task-template.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-task"></i>
                                Task manager
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                @can('can_create_tasks')
                                    <li class="{{ isActive('task.index') }}">
                                        <a href="{{ route('task.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Lista
                                        </a>
                                    </li>
                                @endcan

                                <li class="{{ isActive('task.assegnati') }}">
                                    <a href="{{ route('task.assegnati') }}">
                                        <i class="metismenu-icon"></i>
                                        Task assegnati
                                    </a>
                                </li>

                                @can('can_create_tasks_template')
                                    <li class="{{ isActive('task-template.index') }}">
                                        <a href="{{ route('task-template.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Template
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>

                        <li class="{{ isActive(['commessa.*', 'commessa-template.*', 'commessa-utils.*', 'squadra.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-objects-horizontal-left"></i>
                                Commesse
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('commessa.index') }}">
                                    <a href="{{ route('commessa.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Lista
                                    </a>
                                </li>

                                @can('can_create_commesse_template')
                                <li class="{{ isActive('commessa-template.index') }}">
                                    <a href="{{ route('commessa-template.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Template
                                    </a>
                                </li>
                                @endcan

                                @can('can_create_commesse_squadre')
                                    <li class="{{ isActive('squadra.index') }}">
                                        <a href="{{ route('squadra.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Squadre
                                        </a>
                                    </li>
                                @endcan

                                @can('can_show_commesse_utility')
                                <li class="{{ isActive('commessa-utils.*') }}">
                                    <a href="#">
                                        Utility
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li class="{{ isActive('commessa-utils.scheduler-commesse') }}"><a href="{{ route('commessa-utils.scheduler-commesse') }}">Schedulazioni commesse</a></li>
                                        <li class="{{ isActive('commessa-utils.scheduler') }}"><a href="{{ route('commessa-utils.scheduler') }}">Schedulazioni risorse</a></li>
                                        <li class="{{ isActive('commessa-utils.sovrapposizioni') }}"><a href="{{ route('commessa-utils.sovrapposizioni') }}">Check sovrap. fasi</a></li>
                                        <li class="{{ isActive('commessa-utils.map') }}"><a href="{{ route('commessa-utils.map') }}">Mappa commesse</a></li>
                                    </ul>
                                </li>
                                @endcan

                            </ul>
                        </li>

                        @if(auth()->user()->can('can_create_ham') || auth()->user()->can('can_create_ham_terminali'))
                        <li class="{{ isActive(['human-activity.*', 'device.*']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-desktop"></i>
                                Human activity
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                @can('can_create_ham')
                                <li class="{{ isActive('human-activity.index') }}">
                                    <a href="{{ route('human-activity.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Activity monitor
                                    </a>
                                </li>
                                @endcan
                                @can('can_create_ham_terminali')
                                    <li class="{{ isActive(['device.index', 'device.create', 'device.edit']) }}">
                                        <a href="{{ route('device.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Terminali
                                        </a>
                                    </li>
                                    <li class="{{ isActive('device.configuration') }}">
                                        <a href="{{ route('device.configuration') }}">
                                            <i class="fas fa-cog"></i>
                                            Conf. globale
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        <li class="{{ isActive(['modot23.*', 'microformazione.index', 'microformazione.alcol']) }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-info-square"></i>
                                Prevenzione
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
{{--                                @can('can_create_mancati_infortuni')--}}
                                <li class="{{ isActive('modot23.index') }}">
                                    <a href="{{ route('mod-ot23.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Mancati infortuni
                                    </a>
                                </li>
{{--                                @endcan--}}
                                <li class="{{ isActive('microformazione.index') }}">
                                    <a href="{{ route('microformazione.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Micro formazione
                                    </a>
                                </li>
                                <li class="{{ isActive('microformazione.alcol') }}">
                                    <a href="{{ route('microformazione.alcol') }}">
                                        <i class="metismenu-icon"></i>
                                        Stop alcol e droghe
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ isActive('cliente.*') }}">
                            <a href="{{ route('cliente.index') }}">
                                <i class="metismenu-icon bx bx-user-pin"></i> Clienti e fornitori
                            </a>
                        </li>

                        @can('testing')
                            <li class="{{ isActive(['fattura.*, iva.*']) }}">
                                <a href="#">
                                    <i class="metismenu-icon bx bx-copy-alt"></i>
                                    Fatture
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <li class="{{ isActive('fattura.index') }}">
                                        <a href="{{ route('fattura.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Lista
                                        </a>
                                    </li>
                                    <li class="{{ isActive('iva.index') }}">
                                        <a href="{{ route('iva.index') }}">
                                            <i class="metismenu-icon"></i>
                                            Iva ed esenzioni
                                        </a>
                                    </li>
                                </ul>
                            </li>


                        @endcan

                        <li class="{{ isActive('microformazione.manuale') }}">
                            <a href="{{ url('/microformazione/manuale') }}">
                                <i class="metismenu-icon bx bx-book-alt"></i> Manuale utente
                            </a>
                        </li>

                        @if(Auth::user()->power_user || Auth::user()->azienda_id)
                        <li class="app-sidebar__heading">Power user</li>
                        <li class="">
                            <a href="#">
                                <i class="metismenu-icon bx bx-check-shield"></i>
                                Autorizzazioni
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-azi-aut.index') }}">
                                        Azienda
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-inf-aut.index') }}">
                                        Infosituata
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-tim-aut.index') }}">
                                        Rilevazione presenze
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-comun-aut.index') }}">
                                        Comunicazioni
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-rap-aut.index') }}">
                                        Rapportini
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-che-aut.index') }}">
                                        Checklist
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-tas-aut.index') }}">
                                        Task manager
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-com-aut.index') }}">
                                        Commesse
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-ham-aut.index') }}">
                                        Human activity
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-pre-aut.index') }}">
                                        Prevenzione
                                    </a>
                                </li>
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-cli-aut.index') }}">
                                        Clienti e fornitori
                                    </a>
                                </li>
                                @can('testing')
                                <li class="">
                                    <a href="javascript:void(0);" tabindex="1"
                                       class="rapportini-autorizzazioni"
                                       data-route="{{ route('mod-fat-aut.index') }}">
                                        Fatture
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->superadmin)
                        <li class="app-sidebar__heading">Amministrazione</li>
                        <li class="{{ isActive('azienda.*') }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-building"></i>
                                Aziende
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('azienda.index') }}">
                                    <a href="{{ route('azienda.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Lista
                                    </a>
                                </li>
                                <li class="{{ isActive('azienda.create') }}">
                                    <a href="{{ route('azienda.create') }}">
                                        <i class="metismenu-icon"></i>
                                        Crea nuovo
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive('user.*') }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-user"></i>
                                Utenti
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('user.index') }}">
                                    <a href="{{ route('user.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Lista
                                    </a>
                                </li>
                                <li  class="{{ isActive('user.create') }}">
                                    <a href="{{ route('user.create') }}">
                                        <i class="metismenu-icon"></i>
                                        Crea nuovo
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive('tipologia-scadenza.*') }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-list-ul"></i>
                                Tipologia scadenza
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('tipologia-scadenza.index') }}">
                                    <a href="{{ route('tipologia-scadenza.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Lista
                                    </a>
                                </li>
                                <li  class="{{ isActive('tipologia-scadenza.create') }}">
                                    <a href="{{ route('tipologia-scadenza.create') }}">
                                        <i class="metismenu-icon"></i>
                                        Crea nuovo
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive('package.*') }}">
                            <a href="#">
                                <i class="metismenu-icon bx bx-list-ul"></i>
                                Package
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul>
                                <li class="{{ isActive('package.index') }}">
                                    <a href="{{ route('package.index') }}">
                                        <i class="metismenu-icon"></i>
                                        Lista
                                    </a>
                                </li>
                                <li  class="{{ isActive('package.create') }}">
                                    <a href="{{ route('package.create') }}">
                                        <i class="metismenu-icon"></i>
                                        Crea nuovo
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                </div>
            </div>
            @endcan

        </div>
        <div class="app-main__outer">
            <div class="app-main__inner p-0">

                @yield('header')

                <div class="p-3">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>
</div>

@yield('modal')
<!--SCRIPTS INCLUDES-->

@include('layouts.modals.destroy')
@include('layouts.modals.confirm')
@include('dashboard.upload.s3.modals.attachment-update-label')

@include('dashboard.ticket.modals.create')

<!--DRAWER START-->
<div class="app-drawer-wrapper">
    {{--    <div class="drawer-nav-btn">--}}
    {{--        <button type="button" class="hamburger hamburger--elastic is-active">--}}
    {{--            <span class="hamburger-box"><span class="hamburger-inner"></span></span>--}}
    {{--        </button>--}}
    {{--    </div>--}}
    <div class="drawer-content-wrapper">
        <div class="scrollbar-container">
            <h3 class="drawer-heading">Operation</h3>
            <div class="drawer-section">
                <div id="drawer">
                    <div class="row">
                        <div class="col-lg-12">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="app-drawer-overlay d-none animated fadeIn"></div>
<!--DRAWER END-->

<!--CORE-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<script src="{{ url('assets/js/scripts-init/app.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/demo.js') }}"></script>
{{--<script src="{{ url('assets/js/vendors/charts/apex-charts.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/scripts-init/charts/apex-charts.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/scripts-init/charts/apex-series.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/vendors/charts/charts-sparklines.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/scripts-init/charts/charts-sparklines.js') }}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="{{ url('assets/js/scripts-init/charts/chartsjs-utils.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/charts/chartjs.js') }}?v=1.0"></script>
<script src="{{ url('assets/js/vendors/form-components/clipboard.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/clipboard.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ url('assets/js/vendors/form-components/datepicker.it-IT.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/datepicker.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/bootstrap-multiselect.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>--}}
<script src="{{ url('assets/js/vendors/select2-4.0.13/dist/js/select2.min.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/input-select.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/form-validation.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/form-validation.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/form-wizard.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/form-wizard.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/input-mask.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/input-mask.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/wnumb.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/range-slider.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/range-slider.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/textarea-autosize.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/textarea-autosize.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/toggle-switch.js') }}"></script>
<script src="{{ url('assets/js/vendors/blockui.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/blockui.js') }}"></script>
<script src="{{ url('assets/js/vendors/calendar.js') }}"></script>
<script src="{{ url('assets/js/vendors/locale-all.js') }}"></script>
<script src="{{ url('assets/js/vendors/carousel-slider.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/carousel-slider.js') }}"></script>
<script src="{{ url('assets/js/vendors/circle-progress.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/circle-progress.js') }}"></script>
<script src="{{ url('assets/js/vendors/count-up.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/count-up.js') }}"></script>
<script src="{{ url('assets/js/vendors/cropper.js') }}"></script>
<script src="{{ url('assets/js/vendors/jquery-cropper.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/image-crop.js') }}"></script>
<script src="{{ url('assets/js/vendors/gmaps.js') }}"></script>
<script src="{{ url('assets/js/vendors/jvectormap.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/maps-word-map.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/maps.js') }}"></script>
<script src="{{ url('assets/js/vendors/guided-tours.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/guided-tours.js') }}"></script>
<script src="{{ url('assets/js/vendors/ladda-loading.js') }}"></script>
<script src="{{ url('assets/js/vendors/spin.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/ladda-loading.js') }}"></script>
<script src="{{ url('assets/js/vendors/rating.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/rating.js') }}"></script>
<script src="{{ url('assets/js/vendors/scrollbar.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/scrollbar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src="{{ url('assets/js/scripts-init/toastr.js') }}"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>--}}
{{--<script src="{{ url('assets/js/scripts-init/sweet-alerts.js') }}"></script>--}}
<script src="{{ url('assets/js/vendors/treeview.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/treeview.js') }}"></script>
{{--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.10.19/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="{{ url('assets/js/vendors/tables.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/scripts-init/tables.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/vendors/summernote/dist/summernote.min.js') }}"></script>--}}
{{--<script src="{{ url('assets/js/vendors/summernote/dist/summernote-bs4.min.js') }}"></script>--}}
<script src="{{ url('assets/js/vendors/form/jquery.form.min.js') }}"></script>
<script src="{{ url('assets/tags-input/dist/jquery.tagsinput.min.js') }}"></script>
<script src="{{ url('assets/js/vendors/FitVids/jquery.fitvids.js') }}"></script>

{{-- Tiny MCE --}}
{{--<script src="https://cdn.tiny.cloud/1/1opy22wr19sfyoz1fzd7d6hjd99uc8nsg8fya0r2e37wga4x/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>--}}
{{--<script src="{{ url('js/tinymce/langs/it.js') }}"></script>--}}

{{-- Summernote --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

{{-- Html2Canvas --}}
<script src="{{ url('js/html2canvas/html2canvas.min.js') }}"></script>


{{-- Datatable --}}
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js"></script>

<script src="{{ url('assets/leaflet/leaflet.js') }}"></script>
<script src="{{ url('assets/leaflet.markercluster/dist/leaflet.markercluster.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-4-autocomplete/dist/bootstrap-4-autocomplete.min.js" crossorigin="anonymous"></script>
<script src="{{ url('js/datatables.js?v=').time() }}"></script>

{{-- Dropzone --}}
{{--<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>--}}
<script src="{{ url('js/dropzone/dropzone.min.js') }}"></script>


<script>
    var url = '{{ url('/') }}';
</script>

<script src="{{ url('assets/js/scripts-init/calendar.js') }}?v=1.1"></script>


@if(isActive(['timbrature.*', 'timbrature.mensili', 'timbrature-permessi.*']))
    <script src="{{ url('js/timbrature.js?v=').time() }}"></script>
@endif


@if(isActive(['carburante.*']))
    <script src="{{ url('js/carburante.js?v=').time() }}"></script>
@endif

@if(isActive(['topic.*']))
    <script src="{{ url('js/topic.js?v=').time() }}"></script>
@endif

@if(isActive(['whatsapp.*']))
    <script src="{{ url('js/whatsapp.js?v=').time() }}"></script>
@endif

@if(isActive(['rapportini.*']))
    <script src="{{ url('js/rapportini.js?v=').time() }}"></script>
@endif

@if(isActive(['log-visualizzazioni.*']))
    <script src="{{ url('js/log-visualizzazioni.js?v=').time() }}"></script>
@endif

@if(isActive(['checklist-template.*']))
    <script src="{{ url('js/checklist-tpl.js?v=').time() }}"></script>
@endif

@if(isActive(['checklist.*', 'infosituata.check']))
    <script src="{{ url('js/checklist.js?v=').time() }}"></script>
@endif

@if(isActive(['task-template.*', 'task.*']))
    <script src="{{ url('js/task.js?v=').time() }}"></script>
    <script src="{{ url('js/task-tpl.js?v=').time() }}"></script>
@endif

@if(isActive(['commessa-template.*', 'commessa.*']))
    <script src="{{ url('js/commessa-tpl.js?v=').time() }}"></script>
@endif

@if(isActive(['scadenzario.*', 'dashboard.*']))
    <script src="{{ url('js/commessa.js?v=').time() }}"></script>
@endif

@if(isActive(['commessa.*']))
    <script src="{{ url('js/gantt.js?v=').time() }}"></script>
    <script src="{{ url('js/commessa.js?v=').time() }}"></script>
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
@endif

@if(isActive(['commessa-utils.*']))
    <script src="{{ url('js/commessa-utils.js?v=').time() }}"></script>
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
@endif

@if(isActive(['squadra.*']))
    <script src="{{ url('js/squadra.js?v=').time() }}"></script>
@endif

@if(isActive(['tipologia-scadenza.*', 'scadenzario.*']))
    <script src="{{ url('js/tipologia-scadenza.js?v=').time() }}"></script>
@endif

@if(isActive(['human-activity.index', 'device.index', 'timbrature.*']))
    <script src="{{ url('js/map.js?v=').time() }}"></script>
@endif

@if(isActive(['iva.*']))
    <script src="{{ url('js/iva.js?v=').time() }}"></script>
@endif

@if(isActive(['fattura.*']))
    <script src="{{ url('js/fattura.js?v=').time() }}"></script>
@endif

@if(isActive(['mod-ot23_2024.*', 'mod-ot23']))
    <script src="{{ url('js/modot23.js?v=').time() }}"></script>
@endif

@if(Auth::user()->power_user || Auth::user()->azienda_id)
    <script src="{{ url('js/autorizzazioni.js?v=').time() }}"></script>
@endif

<script src="{{ url('js/clienti.js?v=').time() }}"></script>

<script>
    var _url = '{{ url('/') }}';
    var googleAutocomplete = null;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_PLACE_API_KEY')}}&libraries=places"></script>

<script src="{{ url('js/ticket.js?v=').time() }}"></script>
<script src="{{ url('js/plugins.js?v=').time() }}"></script>
<script src="{{ url('js/forms.js?v=').time() }}"></script>

</body>

<div id="modal-ajax-html"></div>
<div id="modal-ajax-html-2"></div>

</html>
