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

            <div class="col-md-4" id="frmMark">
                <form action="{{ route('commessa.store-mark', $commessa->id) }}" class="ns" method="POST" data-callback="$('#markSuccess').removeClass('d-none');$('#frmMark').addClass('d-none');">
                    @csrf
                    <input type="hidden" name="side" value="in">

                        <div class="main-card mb-3 card">
                            <div class="card-header">Seleziona la fase / sottofase</div>
                            <ul class="todo-list-wrapper list-group list-group-flush">
                                @foreach($list as $l)
                                    <li class="list-group-item">
                                        <div class="p-2 px-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="commessa_id" id="radio-{{ $l->id }}" value="{{ $l->id  }}">
                                                <label class="form-check-label" for="radio-{{ $l->id }}">
                                                    {{ $l->parent->label }}
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="d-block text-right card-footer">
                                <button class="btn btn-success btn-lg">Salva</button>
                            </div>
                        </div>
                </form>
            </div>

            <div class="col-md-4 d-none" id="markSuccess">
                @component('layouts.components.alerts.success')
                    Timbratura di inizio produzione creata
                @endcomponent
            </div>
        @endif
    </div>

@endsection
