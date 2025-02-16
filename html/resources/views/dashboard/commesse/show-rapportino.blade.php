@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => 'Rapportino', 'icon' => 'bx bx-bar-chart-alt', 'back' => null])
        {{ $el->titolo }}
    @endcomponent
@endsection

@section('content')

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title">
                <i class="header-icon bx bx-bar-chart-alt icon-gradient bg-love-kiss"> </i>
                {{ $el->titolo }}
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-7">
                    <div class="ml-4 mt-4">
                        @component('dashboard.commesse.analisi.components.show-rapportino', ['el' => $el])
                        @endcomponent
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mr-4 mt-4">
                        @component('dashboard.upload.component.rapportino-upload-file', ['item' => $el])
                            Rapportino
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a class="btn btn-light"
               href="{{ route('commessa.show', $el->commesse_root_id) }}">
                Vai alla commessa
            </a>
        </div>
    </div>

@endsection
