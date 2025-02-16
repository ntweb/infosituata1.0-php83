@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'chat', 'icon' => 'bx bxl-whatsapp'])
        Whatsapp
    @endcomponent
@endsection

@section('content')

    <div class="app-inner-layout__wrapper p-0">

        <div class="row">
            @if(isset($utenti))
            <div class="col-md-4" @if($hide_utenti_list) style="display: none;" @endif>
                <div class="app-inner-layout__sidebar card">
                    <div class="app-inner-layout__sidebar-header">
                        <ul class="nav flex-column">
                            <li class="pt-4 pl-3 pr-3 pb-3 nav-item">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-search"></i>
                                        </div>
                                    </div>
                                    <input placeholder="Filtra utenti..." type="text" class="form-control"></div>
                            </li>
                            <li class="nav-item-header nav-item">Utenti</li>
                        </ul>
                    </div>
                    <ul class="nav flex-column">
                        @foreach($utenti as $u)
                        <li class="nav-item">
                            <button type="button" tabindex="0" class="dropdown-item btnLoadWhatsappMessages" id="btnLoadWhatsappMessages{{ $u->id }}" data-url="{{ route('whatsapp.show-chat', $u->id) }}">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ title_case(strtolower($u->label)) }}</div>
                                            {{-- <div class="widget-subheading">Aenean vulputate eleifend tellus.</div> --}}
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
            @endif
            <div class="{{ $hide_utenti_list ? 'col-md-12' : 'col-md-8' }}" id="chat-container">

            </div>
        </div>

    </div>

@endsection
