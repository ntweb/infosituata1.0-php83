@extends('layouts.landing')

@section('content')

    <div class="row">
        <div class="{{ count($attachments) ? 'col-md-8' : 'col-md-12' }}">

            @if($el->extras2)
                <div class="card-hover-shadow-2x mb-3 card">
                    <div class="card-header">{{ strtoupper($el->extras1) }}</div>
                    <div class="card-body">
{{--                        <iframe src="{{ $el->extras2 }}" frameborder="0"></iframe>--}}
                        <div class="infosituata-embed">
                            <embed src="{{ $el->extras2 }}" style="width: 100%; height: 600px;">
                        </div>
                    </div>
                    <div class="d-block text-right card-footer">
                        <a href="{{ url('/') }}" class="mr-2 btn btn-link btn-sm">Ritorna su INFOSITUATA</a>
                    </div>
                </div>
            @endif

            @if(trim($el->big_extras1) != '')
            <div class="card-hover-shadow-2x mb-3 card">
                <div class="card-header">{{ strtoupper($el->extras1) }}</div>
                <div class="card-body">
                    <div class="infosituata-embed">
                        {!! $el->big_extras1 !!}
                    </div>
                </div>
                <div class="d-block text-right card-footer">
                    <a href="{{ url('/') }}" class="mr-2 btn btn-link btn-sm">Ritorna su INFOSITUATA</a>
                </div>
            </div>
            @endif

        </div>

        @if(count($attachments))
            <div class="col-md-4">

                <div class="card-hover-shadow-2x mb-3 card">
                    <div class="card-header">Allegati</div>
                    <div class="card-body">
                        <ul>
                            @foreach($attachments as $a)
                            <li>
                                <a href="{{  route('s3.get', $a->id) }}" target="_blank">
                                    {{ Str::title($a->label) }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        @endif
    </div>

    @if(count($scadenze))
    <div class="row">
        <div class="col-md-12">
            <div class="card-hover-shadow-2x mb-3 card">
                <div class="card-header">Scadenze</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-0 table table-hover">
                            <thead>
                            <tr>
                                <th class="no-border-top">Etichetta</th>
                                <th class="no-border-top"><i class="fa fa-calendar"></i> Data scadenza</th>
                                <th class="no-border-top"><i class="fa fa-check"></i> Check</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($scadenze as $scadenza)
                                <tr>
                                    <td>{{ $scadenza->detail ? Str::title($scadenza->detail->label) : 'Altro' }}</td>
                                    <td>{{ data($scadenza->end_at) }}</td>
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
                                        @can('can-create')
                                            <a href="{{ route('scadenzario.edit', [$scadenza->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-block text-right card-footer">
                    <a href="{{ url('/') }}" class="mr-2 btn btn-link btn-sm">Ritorna su INFOSITUATA</a>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
