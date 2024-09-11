@extends('layouts.dashboard')

@section ('header')
    @component('layouts.components.header', ['subtitle' => 'Lista dei template', 'icon' => 'bx bxs-objects-horizontal-left', 'right_component' => 'dashboard.commesse-tpl.components.index-header'])
        Template commessa
    @endcomponent
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            @if(count($list))
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Template</h5>
                        <div class="table-responsive pb-10">
                            <table class="mb-0 table table-hover" id="dashboard_user_index">
                                <thead>
                                <tr>
                                    <th>Etichetta</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
                                        <td>{{ Str::title($el->label) }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('commessa-template.edit', [$el->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-edit mr-2"></i> Edit</a>
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
