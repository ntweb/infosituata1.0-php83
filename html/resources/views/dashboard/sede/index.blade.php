@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista delle sedi', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.sede.components.index-header'])
        Sedi
    @endcomponent
@endsection

@section('search')
    <div class="search-wrapper @if(request()->has('q')) active @endif">
        <div class="input-holder">
            <input type="text" class="search-input" placeholder="Cerca" data-route="{{ route('sede.index' ) }}?q=" value="{{ request()->get('q') }}">
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
                        <h5 class="card-title">Sedi</h5>
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
                                    <th>Etichetta</th>
                                    <th>Città</th>
                                    <th>Indirizzo</th>
                                    <th>Azienda</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
{{--                                        <th scope="row">{{ $el->id }}</th>--}}
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td>{{ Str::title($el->citta) }}</td>
                                        <td>{{ Str::limit($el->indirizzo, 20, '...') }}</td>
                                        <td>{{ Str::title($el->azienda->label) }}</td>
                                        <td class="text-right">
                                            @can('can_create_sedi')
                                            <a href="{{ route('sede.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            @endcan
                                        </td>
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
