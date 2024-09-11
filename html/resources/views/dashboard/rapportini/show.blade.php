@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => 'Rapportino', 'icon' => 'bx bx-notepad', 'back' => route('rapportini.index'), 'right_component' => 'dashboard.rapportini.components.create-header', 'el' => $el])
        {{ $el->titolo }}
    @endcomponent
@endsection

@section('content')

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                {{ $el->item ? $el->item->label : $el->titolo }}
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-7">
                    @include('dashboard.rapportini.forms.create')
                </div>
                <div class="col-md-5">
                    @component('dashboard.upload.s3.upload', ['reference_id' => $el->id, 'reference_table' => 'rapportini'])
                        Rapportino
                    @endcomponent
                </div>
            </div>



            <div class="row">
                <div class="col">
                    <hr>
                </div>
                <div class="col-12">
                    <p>
                        <span>Redatto da: <strong>{{ $el->user->name }}</strong></span>
                        <span class="ml-2">il: <strong>{{ dataOra($el->created_at) }}</strong></span>
                    </p>
                </div>
            </div>
        </div>
    </div>



@endsection
