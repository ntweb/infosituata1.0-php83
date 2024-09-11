@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => $side == 'in' ? 'Crea una timbratura di produzione' : 'Termina una produzione', 'icon' => 'bx bxs-objects-horizontal-left', 'right_component' => isset($el) ? 'dashboard.commesse.components.edit-header' : null])
        {{ $commessa->label }}
    @endcomponent
@endsection

@section('content')
    <div class="row">

        @if($error)
            <div class="col-md-12">
                @component('layouts.components.alerts.error')
                    {{ $error }}
                @endcomponent
            </div>
        @else
            <div class="col-md-12">
                @component('layouts.components.alerts.success')
                    Timbratura di fine produzione acquisita
                @endcomponent
            </div>
        @endif
    </div>

@endsection
