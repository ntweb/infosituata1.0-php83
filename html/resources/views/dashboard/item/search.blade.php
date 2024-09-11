@if($items->count())
    <div class="table-responsive">
        <table class="mb-0 table table-sm">
            <thead>
            <tr>
                <th>Etichetta</th>
                <th>Tags</th>
                <th>Sovrapp.</th>
                <th>Eventi</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $el)
                <tr>
                    <td>{{ $el->label }}</td>
                    <td>
                        @component('layouts.components.tags-viewer')
                            {{ $el->tags }}
                        @endcomponent
                    </td>
                    <td>
                        @if(isset($nodeOther[$el->id]))
                            <div class="d-flex align-items-center">
                                <i class="bx bxs-error-circle text-warning mr-2" style="font-size: 20px"></i>
                                <div class="d-flex flex-column">
                                    <small>{{ $nodeOther[$el->id]['period'] }}</small>
                                    <small>
                                        {{ $nodeOther[$el->id]['message'] }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if(isset($eventi[$el->id]))
                            <div class="d-flex align-items-center">
                                <i class="bx bxs-error-circle text-warning mr-2" style="font-size: 20px"></i>
                                <div class="d-flex flex-column">
                                    <small>{{ $eventi[$el->id]['period'] }}</small>
                                    <small>{{ $eventi[$el->id]['titolo'] }}</small>
                                    <small><a href="javascript:void(0)" class="eventoReadMore" data-toggle="#evento-{{ $eventi[$el->id]['id'] }}">Leggi di più</a></small>
                                    <small style="display:none" id="evento-{{ $eventi[$el->id]['id'] }}">
                                        {{ $eventi[$el->id]['descrizione'] }}
                                        <br>
                                        <a href="javascript:void(0)" class="eventoReadLess" data-toggle="#evento-{{ $eventi[$el->id]['id'] }}">Nascondi</a>
                                    </small>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="text-right">
                        @if(!isset($children[$el->id]))
                            <div id="item-id-{{ $el->id }}-assign">
                                <button type="button"
                                        class="border-0 btn-transition btn btn-sm btn-outline-light addItemNode"
                                        data-route="{{ route('commessa-node.store') }}"
                                        data-item-id="{{ $el->id }}"
                                        data-to="{{ $node->id }}">
                                    Assegna
                                    <i class="bx bx-right-arrow-alt"></i>
                                </button>
                            </div>
                            <div id="item-id-{{ $el->id }}-assigned" style="display: none">
                                @component('layouts.components.labels.warning')
                                    assegnato
                                @endcomponent
                            </div>
                        @else
                            @component('layouts.components.labels.warning')
                                assegnato
                            @endcomponent
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    @component('layouts.components.alerts.warning')
        Nessun risultato trovato
    @endcomponent
@endif
