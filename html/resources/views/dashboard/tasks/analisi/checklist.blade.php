@if(count($list))
    <div class="p-2">
        <div class="table-responsive">
            <table class="mb-0 table table-sm" id="checklist_dt">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Checklist</th>
                    <th>Fase / sottofase</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $el)
                    <tr class="pointer createChecklistCommessa" data-route="{{ route('checklist.show', $el->id) }}">
                        <td>{{ Str::title($el->tpl->label) }}</td>
                        <td>{{ Str::title($el->node->label) }}</td>
                        <td>
                            <div class="d-flex flex-column align-items-end">
                                <small>{{ $el->created_at }}</small>
                                <small>{{ $el->username }}</small>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="p-4">
        @component('layouts.components.alerts.info')
            Nessuna checklist trovata
        @endcomponent
    </div>
@endif

