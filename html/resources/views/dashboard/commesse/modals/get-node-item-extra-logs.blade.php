@php
    $commessa = $node->root;
    $can_create_risorse_log = false;
    if (\Illuminate\Support\Facades\Gate::allows('risorse_create_log', $commessa)) {
        $can_create_risorse_log = true;
    }
@endphp

<div class="modal fade" id="modalNodeLogs" tabindex="-1" role="dialog" aria-labelledby="modalNodeLogs" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $node->label }} <small>- {{ $node->parent->label }}</small></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="node-create-body">
                <div class="row">
                    @if($can_create_risorse_log)
                        <div class="col-md-4">
                            <form class="ns" action="{{ route('commessa-node.store-log', $node->id) }}" autocomplete="none" method="post" data-callback="refreshLogsItemTable('{{ route('commessa-node.logs', [$node->id, '_render_table' => true]) }}'); refreshOverviewTable();">
                                @csrf

                                @component('layouts.components.forms.text', ['name' => 'note', 'value' => null, 'class' => 'col-md-12'])
                                    Etichetta
                                @endcomponent

                                @component('layouts.components.forms.number', ['name' => 'item_costo', 'value' => 0.00, 'class' => 'col-md-12', 'min' => 0, 'step' => 0.01])
                                    Costo aziendale
                                @endcomponent

                                @component('layouts.components.forms.date-native', ['name' => 'data_attribuzione', 'value' => null, 'class' => 'col-md-12'])
                                    Data attribuzione
                                @endcomponent

                                <div class="col-12">
                                    <hr>
                                    <button class="btn btn-primary">Salva</button>
                                </div>

                            </form>
                        </div>
                    @endif
                    <div class="{{ $can_create_risorse_log ? 'col-md-8' : 'col-md-12' }} pb-0" id="logs-table">
                        @include('dashboard.commesse.analisi.components.item-logs-table')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
