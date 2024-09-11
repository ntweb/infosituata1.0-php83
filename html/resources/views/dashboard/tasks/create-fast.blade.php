@extends('layouts.dashboard')

@section('header')
    @component('layouts.components.header', ['subtitle' => isset($el) ? 'Modifica' : 'Crea nuovo', 'icon' => 'bx bx-task', 'right_component' => isset($el) ? 'dashboard.tasks.components.edit-header' : null, 'el' => isset($el) ? $el : null ,'back' => isset($el) ? route('task.index') : null])
        Task manager
    @endcomponent
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            @include('layouts.components.alerts.alert')
            @include('dashboard.tasks.forms.create-fast')
        </div>

    </div>

@endsection

