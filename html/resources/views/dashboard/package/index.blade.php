@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei pacchetti', 'icon' => 'pe-7s-menu', 'right_component' => 'dashboard.package.components.index-header'])
        Package
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Package</h5>
                    <div class="table-responsive">
                        <table class="mb-0 table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Etichetta</th>
                                <th class="text-right">Sedi</th>
                                <th class="text-right">Gruppi</th>
                                <th class="text-right">Utenti</th>
                                <th class="text-right">Cloud</th>
                                <th class="text-right">Stato</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $el)
                            <tr>
                                <th scope="row">{{ $el->id }}</th>
                                <td>{{ Str::title($el->label) }}</td>
                                <td class="text-right">{{ $el->sedi }}</td>
                                <td class="text-right">{{ $el->gruppi }}</td>
                                <td class="text-right">{{ $el->utenti }}</td>
                                <td class="text-right">{{ isa_convert_bytes_to_specified($el->size, 'G') }} Gb</td>
                                <td class="text-right">
                                    @if($el->active)
                                        @component('layouts.components.labels.success')
                                            attivo
                                        @endcomponent
                                    @else
                                        @component('layouts.components.labels.error')
                                            sospeso
                                        @endcomponent
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('package.edit', [$el->id]) }}" class="btn btn-primary btn-sm">Edit</a>
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
