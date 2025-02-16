@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => $el->oggetto, 'icon' => 'pe-7s-home', 'back' => $back])
        SMS
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">{{ $el->oggetto }}</div>
                <div class="scroll-area-sm">
                    <div class="scrollbar-container ps ps--active-y">
                        <div class="chat-wrapper p-1">
                            <div class="chat-box-wrapper">
                                <div>
                                    <div class="avatar-icon-wrapper mr-1">
                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                        <div class="avatar-icon avatar-icon-lg rounded">
                                            <img src="{{ url('assets/images/avatars/1.jpg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="chat-box">
                                        {!! $el->messaggio !!}
                                    </div>
                                    <small class="opacity-6">
                                        <i class="fa fa-calendar-alt mr-1"></i>
                                        {{ dataOra($el->created_at) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 200px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 75px;"></div></div></div>
                </div>
            </div>
        </div>

    </div>

@endsection
