<div class="row">
    <div class="col-md-12">

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Human activity | {{ $today ? data(\Carbon\Carbon::now()) : 'Log' }}</h5>
                <div class="table-responsive">
                    <table class="mb-0 table table-hover">
                        <thead>
                        <tr>
                            @if(Auth::user()->superadmin)
                            <th>#</th>
                            @endif
                            <th>Utente</th>
                            @if(!Auth::user()->superadmin)
                                <th>Azienda</th>
                            @endif
{{--                            <th class="text-center" style="width: 150px;">Heart mon.</th>--}}
{{--                            <th class="text-center" style="width: 60px;"><i class="fas fa-heartbeat text-danger animated heartBeat infinite"></i></th>--}}
{{--                            <th class="text-center" style="width: 150px;">Stress lv.</th>--}}
                            <th class="text-center" style="width: 150px;">Man down</th>
                            <th class="text-center" style="width: 120px;">Alert</th>
                            <th style="width: 128px;"></th>
                            <th class="text-center" style="width: 120px">Check</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $el)
                            <tr>
                                @if(Auth::user()->superadmin)
                                <td scope="row">{{ $el->id }}</td>
                                @endif
                                <td> <a href="javascript:void(0)" class="btn btn-light btn-sm" data-toggle="popover-custom-bg" data-bg-class="text-light bg-focus" data-title="{{ Str::title($el->device->type->brand.' | '.$el->device->type->label) }}" data-content="{{ Str::title($el->device->type->hw) }} ID: {{ $el->device->identifier }}" data-original-title title>
                                        {{ $el->utente ? Str::title($el->utente->label) : 'ND' }}
                                    </a>
                                </td>
                                @if(!Auth::user()->superadmin)
                                    <td>{{ Str::title($el->azienda->label) }}</td>
                                @endif
{{--                                <td class="text-center">--}}
{{--                                    @if($el->device->type->hearth_monitor)--}}
{{--                                        @component('layouts.components.humanactivity.hrm', ['el' => $el])--}}
{{--                                        @endcomponent--}}
{{--                                    @else--}}
{{--                                        <div class="badge badge-light">No data</div>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td class="text-center">--}}
{{--                                    <span class="badge badge-light">{{ $el->hrm_bpm }} bpm</span>--}}
{{--                                </td>--}}
{{--                                <td class="text-center">--}}
{{--                                    @component('layouts.components.humanactivity.stress', ['el' => $el])--}}
{{--                                    @endcomponent--}}
{{--                                </td>--}}
                                <td class="text-center">
                                    @component('layouts.components.humanactivity.mandown', ['el' => $el])
                                    @endcomponent
                                </td>
                                <td class="text-center">
                                    @component('layouts.components.humanactivity.alert', ['el' => $el])
                                    @endcomponent
                                </td>
                                <td class="text-right">
                                    @component('layouts.components.humanactivity.data', ['el' => $el])
                                    @endcomponent
                                </td>
                                <td class="text-center">
                                    @component('layouts.components.humanactivity.check', ['el' => $el])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if (!$today)
                    {{ $list->links('vendor.pagination.default') }}
                @endif

            </div>
        </div>

    </div>
</div>
