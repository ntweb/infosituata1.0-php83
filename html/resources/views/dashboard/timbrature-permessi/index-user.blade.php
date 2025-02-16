@extends('layouts.dashboard')
@section('header')
    @php
        $back = url()->previous() ? url()->previous() : null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Lista permessi', 'icon' => 'pe-7s-home', 'back' => $back, 'right_component' => null])
        Permessi
    @endcomponent

@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @include('dashboard.timbrature-permessi.forms.create')
        </div>
        <div class="col-md-8">
            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Richieste permessi / ferie</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Tipologia</th>
                                    <th>Giorni</th>
                                    <th class="text-right">Stato</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $p)
                                    <tr>
                                        <td>{{ Str::title($p->type) }}</td>
                                        <td>
                                            @component('layouts.components.timbratura.giorni-permesso', ['permesso' => $p])
                                            @endcomponent
                                        </td>
                                        <td class="text-right">
                                            @component('layouts.components.timbratura.stato-permesso', ['permesso' => $p])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessun permesso richiesto
                @endcomponent
            @endif
        </div>
    </div>
@endsection
