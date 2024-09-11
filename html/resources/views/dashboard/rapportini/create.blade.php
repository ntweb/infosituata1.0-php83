@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => 'Crea nuovo', 'icon' => 'bx bx-notepad', 'back' => route('rapportini.index')])
        Crea nuovo rapportino
    @endcomponent
@endsection

@section('content')


    <div class="row">
        <div class="col-md-4" id="target-selection">
            <form action="{{ route('rapportini.create') }}" class="ns-html" data-container="#modal-ajax-html" data-callback="$('#target-selection').remove();openModalRapportino();">
                <div class="mb-3 card main-card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            Seleziona il target
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">

                            @component('layouts.components.forms.select', ['name' => 'controller', 'id'=>'controller_selector', 'value' => null, 'class' => 'col-md-12', 'elements' => $controllers])
                                Seleziona il target
                            @endcomponent

                            @component('layouts.components.forms.select2-items', ['name' => 'items_id', 'value' => null, 'class' => 'col-md-12'])
                                Associa a
                            @endcomponent

                        </div>
                    </div>
                    <div class="d-block text-right card-footer">
                        <button class="btn btn-primary btn-lg" type="button" id="btnCreateRapportino">Avanti</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
