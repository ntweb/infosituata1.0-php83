@extends('layouts.dashboard')
@section('header')

    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-chat', 'back' => $back])
        Topic
    @endcomponent

@endsection

@section('content')

    <div class="row">

        <div class="col-md-7">
            @include('layouts.components.alerts.alert')
            @if(!isset($el))
                @include('dashboard.topic.forms.topic')
            @endif
            @if(isset($el))
                @include('dashboard.topic.forms.message')
            @endif
        </div>

        @if(isset($el))
            <div class="col-md-5">

                @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'messaggi'])
                    Messaggio
                @endcomponent

                @if($el->user_id == auth()->user()->id)
                    @include('dashboard.topic.forms.create')
                @else
                    @include('dashboard.topic.users-list')
                @endif

                @can('can_create_topic')
                    @component('dashboard.topic.components.delete', ['el' => $el, 'redirect' => route('topic.index')])
                    @endcomponent
                @endcan
            </div>
        @endif

    </div>

@endsection

@section('modal')
    @include('dashboard.messaggio.components.modal-delete-attachment')
@endsection

