@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => 'Crea nuovo', 'icon' => 'bx bx-check-circle', 'back' => route('checklist.index')])
        Crea nuova checklist
    @endcomponent
@endsection

@section('content')


    <div class="row">
        <div class="col-md-4" id="target-selection">
            <form action="{{ route('checklist.create') }}" class="ns-html" data-container="#template-selection" data-callback="$('#target-selection').remove();">
                <div class="mb-3 card main-card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        Seleziona il target
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">

                        @component('layouts.components.forms.select', ['name' => 'reference_controller', 'id'=>'reference_controller_selector', 'value' => null, 'class' => 'col-md-12', 'elements' => $checklists_controllers])
                            Seleziona il target
                        @endcomponent

                        @component('layouts.components.forms.select2-items', ['name' => 'items_id', 'value' => null, 'class' => 'col-md-12'])
                            Associa a
                        @endcomponent

                    </div>
                </div>
                <div class="d-block text-right card-footer">
                    <button class="btn btn-primary btn-lg" type="button" id="btnSelectChecklistTarget">Avanti</button>
                </div>
            </div>
            </form>
        </div>

        <div class="col-md-4" id="template-selection"></div>
    </div>

@endsection
