@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista degli eventi', 'icon' => 'bx bx-calendar-event', 'right_component' => 'dashboard.eventi.components.index-header'])
        Eventi
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca"
                   data-route="{{ route('evento.index' ) }}?q=" value="{{ request()->get('q') }}">
            <button class="search-icon"><span></span></button>
        </div>
        <button class="close"></button>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Eventi</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover table-sm">
                                <thead>
                                <tr>
                                    <th>Associato a</th>
                                    <th>Da</th>
                                    <th>A</th>
                                    <th>Titolo</th>
                                    <th>Descrizione</th>
                                    <th class="text-right">Livello priorità</th>
                                    <th class="text-right"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
                                        <td>{{ $el->item ? Str::title($el->item->label) : 'Item n.d.' }}</td>
                                        <td>{{ data($el->start) }}</td>
                                        <td>{{ data($el->end) }}</td>
                                        <td>{{ strtoupper($el->titolo) }}</td>
                                        <td>{{ $el->descrizione }}</td>
                                        <td class="text-right">
                                            @component('dashboard.eventi.components.labels.priorita', ['el' => $el])
                                            @endcomponent
                                        </td>
                                        @can('can_create_eventi')
                                        <td class="text-right">
                                            <a href="{{ route('evento.edit', [$el->id]) }}"
                                               class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $list->links('vendor.pagination.default') }}

                    </div>
                </div>
            @else
                @component('layouts.components.alerts.warning')
                    Nessun elemento trovato
                @endcomponent
            @endif
        </div>
    </div>

@endsection
