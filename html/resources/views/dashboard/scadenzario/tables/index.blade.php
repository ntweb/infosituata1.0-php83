@if(count($inScadenza) || count($scaduti))

    @php

        $inScadenzaCommesse = $inScadenza->filter(function($c) {
                return $c->commesse_id !== 0;
        });

        $scadutiCommesse = $scaduti->filter(function($c) {
                return $c->commesse_id !== 0;
        });

        $inScadenza = $inScadenza->filter(function($c) {
                return $c->item_id !== 0;
        });

        $scaduti = $scaduti->filter(function($c) {
                return $c->item_id !== 0;
        });
    @endphp
    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title">
                <select class="btn-light multiselect-radio radioFilterScadenze">
                    <option value="">Seleziona tutti</option>
                    <option value=".utente">Utenti</option>
                    <option value=".mezzo">Mezzi</option>
                    <option value=".attrezzatura">Attrezzature</option>
                    <option value=".risorsa">Risorse</option>
                    <option value=".commessa">Commesse</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover" id="dashboard_tip_scadenzario">
                    <thead>
                    <tr>
                        <th class="no-border-top">Etichetta</th>
                        <th class="no-border-top">Scadenza</th>
                        <th class="no-border-top">Data scadenza</th>
                        <th class="no-border-top">Giorni</th>
                        <th class="no-border-top">Data avviso</th>
                        <th class="no-border-top"><i class="fa fa-bullhorn"></i> Utente</th>
                        <th class="no-border-top"><i class="fa fa-bullhorn"></i> Gruppi</th>
                        <th class="no-border-top"><i class="fa fa-check"></i> Check</th>
                        <th class="no-border-top"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inScadenza as $scadenza)
                        <tr class="filter {{ $scadenza->item_controller }}">
                            <td class="text-uppercase">
                                <a href="javascript:void(0)">
                                    <i class="bx bx-map"></i>

                                    {{ $scadenza->item->label }}
                                    @if($scadenza->item->controller == 'mezzo')
                                       <b>[{{ $scadenza->item->extras3 }}]</b>
                                    @endif
                                </a>
                            </td>
                            <td>
                            <span data-toggle="tooltip" data-placement="top" data-original-title="@if($scadenza->detail) {{ $scadenza->detail->label }} @endif">
                                {{ $scadenza->module->label }}
                                /
                                {{ Str::limit(@$scadenza->detail->label, 30, '...') }}
                            </span>
                            </td>
                            <td>{{ data($scadenza->end_at) }}</td>
                            <td>{{ scadeTra($scadenza) }}</td>
                            <td>{{ data($scadenza->advice_at) }}</td>
                            <td>
                                @if($scadenza->advice_item)
                                    @component('layouts.components.labels.success')
                                        si
                                    @endcomponent
                                @else
                                    @component('layouts.components.labels.error')
                                        no
                                    @endcomponent
                                @endif
                            </td>
                            <td>
                                @if(count($scadenza->gruppi))
                                    @component('layouts.components.labels.info')
                                        {{ $scadenza->gruppi->first()->label }}
                                    @endcomponent
                                    @if(count($scadenza->gruppi) > 1)
                                        @component('layouts.components.labels.info')
                                            ...
                                        @endcomponent
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($scadenza->checked_at)
                                    @component('layouts.components.labels.success')
                                        {{ data($scadenza->checked_at) }}
                                    @endcomponent
                                @else
                                    @component('layouts.components.labels.warning')
                                        in scadenza
                                    @endcomponent
                                @endif
                            </td>
                            <td class="text-right">
                                @can('can-create')
                                    <a href="{{ route('scadenzario.edit', [$scadenza->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    @foreach($inScadenzaCommesse as $scadenza)
                        <tr class="filter commessa">
                            <td class="text-uppercase">
                                <a href="javascript:void(0)">
                                    <i class="bx bxs-objects-horizontal-left"></i> {{ $scadenza->label }}
                                </a>
                            </td>
                            <td>
                            <span>
                                {{ Str::limit(@$scadenza->description, 30, '...') }}
                            </span>
                            </td>
                            <td>{{ data($scadenza->end_at) }}</td>
                            <td>{{ scadeTra($scadenza) }}</td>
                            <td>{{ data($scadenza->advice_at) }}</td>
                            <td>-</td>
                            <td>
                                @if(count($scadenza->gruppi))
                                    @component('layouts.components.labels.info')
                                        {{ $scadenza->gruppi->first()->label }}
                                    @endcomponent
                                    @if(count($scadenza->gruppi) > 1)
                                        @component('layouts.components.labels.info')
                                            ...
                                        @endcomponent
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($scadenza->checked_at)
                                    @component('layouts.components.labels.success')
                                        {{ data($scadenza->checked_at) }}
                                    @endcomponent
                                @else
                                    @component('layouts.components.labels.warning')
                                        in scadenza
                                    @endcomponent
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm createAvvisoCommessa" data-route="{{ route('scadenzario.show-commessa', [$scadenza->id, '_check' => true]) }}">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($scaduti as $scadenza)
                        <tr class="filter {{ $scadenza->item_controller }}">
                            <td class="text-uppercase">
                                <a href="javascript:void(0)">
                                    <i class="bx bx-map"></i>
                                    {{ $scadenza->item->label }}
                                </a>
                            </td>
                            <td>
                            <span data-toggle="tooltip" data-placement="top" data-original-title="@if($scadenza->detail) {{ $scadenza->detail->label }} @endif">
                                {{ $scadenza->module->label }}
                                /
                                {{ Str::limit(@$scadenza->detail->label, 30, '...') }}
                            </span>
                            </td>
                            <td>{{ data($scadenza->end_at) }}</td>
                            <td>{{ scadeTra($scadenza) }}</td>
                            <td>{{ data($scadenza->advice_at) }}</td>
                            <td>
                                @if($scadenza->advice_item)
                                    @component('layouts.components.labels.success')
                                        si
                                    @endcomponent
                                @else
                                    @component('layouts.components.labels.error')
                                        no
                                    @endcomponent
                                @endif
                            </td>
                            <td>
                                @if(count($scadenza->gruppi))
                                    @component('layouts.components.labels.info')
                                        {{ $scadenza->gruppi->first()->label }}
                                    @endcomponent
                                    @if(count($scadenza->gruppi) > 1)
                                        @component('layouts.components.labels.info')
                                            ...
                                        @endcomponent
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($scadenza->checked_at)
                                    @component('layouts.components.labels.success')
                                        {{ data($scadenza->checked_at) }}
                                    @endcomponent
                                @else
                                    @if(scaduto($scadenza))
                                        @component('layouts.components.labels.error')
                                            scaduto
                                        @endcomponent
                                    @else
                                        -
                                    @endif
                                @endif

                            </td>
                            <td class="text-right">
                                <a href="{{ route('scadenzario.edit', [$scadenza->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($scadutiCommesse as $scadenza)
                        <tr class="filter commessa">
                            <td class="text-uppercase">
                                <a href="javascript:void(0)">
                                    <i class="bx bxs-objects-horizontal-left"></i> {{ $scadenza->label }}
                                </a>
                            </td>
                            <td>
                            <span>
                                {{ Str::limit(@$scadenza->description, 30, '...') }}
                            </span>
                            </td>
                            <td>{{ data($scadenza->end_at) }}</td>
                            <td>{{ scadeTra($scadenza) }}</td>
                            <td>{{ data($scadenza->advice_at) }}</td>
                            <td>-</td>
                            <td>
                                @if(count($scadenza->gruppi))
                                    @component('layouts.components.labels.info')
                                        {{ $scadenza->gruppi->first()->label }}
                                    @endcomponent
                                    @if(count($scadenza->gruppi) > 1)
                                        @component('layouts.components.labels.info')
                                            ...
                                        @endcomponent
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($scadenza->checked_at)
                                    @component('layouts.components.labels.success')
                                        {{ data($scadenza->checked_at) }}
                                    @endcomponent
                                @else
                                    @component('layouts.components.labels.error')
                                        scaduto
                                    @endcomponent
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm createAvvisoCommessa" data-route="{{ route('scadenzario.show-commessa', [$scadenza->id, '_check' => true]) }}">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

