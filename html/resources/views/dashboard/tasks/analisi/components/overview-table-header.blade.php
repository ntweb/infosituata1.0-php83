@php
    $extraFields = getExtraFieldsStructure($el);

    $can_view_costi = false;
    if (\Illuminate\Support\Facades\Gate::allows('commessa_view_costi', $el)) {
        $can_view_costi = true;
    }
@endphp

<thead class="bg-heavy-rain">
<tr>
    <th class="fit" scope="col"></th>
    <th class="fit" scope="col"></th>
    <th class="fit" scope="col">Dip.</th>
{{--    <th class="text-center" scope="col">Conteggio</th>--}}
    <th class="fit" scope="col">Date prev.</th>
    <th class="fit" scope="col">Date cons.</th>
    @if($can_view_costi)
        <th class="fit text-right" scope="col" data-node-toggle="costi">Costo azi. prev</th>
        <th class="fit text-right" scope="col" data-node-toggle="costi">Costo azi. cons</th>
        <th class="fit text-right" scope="col" data-node-toggle="costi">Riv. cli.</th>
        <th class="fit text-right" scope="col" data-node-toggle="costi">Ric. prev.</th>
        <th class="fit text-right" scope="col" data-node-toggle="costi">Ric. cons.</th>
    @endif
    @foreach($extraFields as $ef)
    <th class="fit text-right" scope="col" data-node-toggle="extras">{{ Str::title($ef->label) }}</th>
    @endforeach
    <th class="fit" scope="col"></th>
    <th class="fit" scope="col"></th>
</tr>
</thead>
