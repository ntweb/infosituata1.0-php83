@php
    $r = 255;
    $g = 255;
    $b = 255;
@endphp
<table class="table-xx table-sm table-hover scheduler">
    <thead>
    <tr>
        <th class="label" id="calcStickyLeftMargin"></th>
        @foreach($period as $d)
            @php
                $sat = $d->dayOfWeek === \Carbon\Carbon::SATURDAY;
                $sun = $d->dayOfWeek === \Carbon\Carbon::SUNDAY;
            @endphp
            <th class="day {{ $sat ? 'td-saturday' : null }} {{ $sun ? 'td-sunday' : null }}">{{ $d->format('d/m') }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($commesse as $index => $commessa)
        @php
            $r -= $index * 4;
            $g -= $index * 4;
            $b -= $index * 4;
        @endphp
        <tr id="tr-item-{{ $commessa->id }}">
            <th class="label" style="line-height: 12px; background-color: rgb({{$r}}, {{$g}}, {{$b}});">
                <strong>{{ $commessa->label }}</strong>
                <br>
                <small>commessa</small>
            </th>
            @foreach($period as $d)
                @php
                    $sat = $d->dayOfWeek === \Carbon\Carbon::SATURDAY;
                    $sun = $d->dayOfWeek === \Carbon\Carbon::SUNDAY;
                @endphp
                <td class="day {{ $sat ? 'td-saturday' : null }} {{ $sun ? 'td-sunday' : null }}" data-item-id="{{ $commessa->id }}" data-day="{{ $d->toDateString() }}" data-n-events="0"></td>
            @endforeach
        </tr>
        @foreach($commessa->children as $node)
            <tr id="tr-item-{{ $node->id }}" class="can-hide-row">
                <th class="label" style="padding-left: 40px; line-height: 12px; background-color: rgb({{$r}}, {{$g}}, {{$b}});">
                    {{ $node->label }}
                    <br>
                    <small>Fase</small>
                </th>
                @foreach($period as $d)
                    @php
                        $sat = $d->dayOfWeek === \Carbon\Carbon::SATURDAY;
                        $sun = $d->dayOfWeek === \Carbon\Carbon::SUNDAY;
                    @endphp
                    <td class="day {{ $sat ? 'td-saturday' : null }} {{ $sun ? 'td-sunday' : null }}" data-item-id="{{ $node->id }}" data-day="{{ $d->toDateString() }}" data-n-events="0"></td>
                @endforeach
            </tr>
            @foreach($node->children as $child)
                @if(!$child->item_id)
                    <tr id="tr-item-{{ $child->id }}" class="can-hide-row">
                        <th class="label" style="padding-left: 80px; line-height: 12px; background-color: rgb({{$r}}, {{$g}}, {{$b}});">
                            {{ $child->label }}
                            <br>
                            <small>Sottofase</small>
                        </th>
                        @foreach($period as $d)
                            @php
                                $sat = $d->dayOfWeek === \Carbon\Carbon::SATURDAY;
                                $sun = $d->dayOfWeek === \Carbon\Carbon::SUNDAY;
                            @endphp
                            <td class="day {{ $sat ? 'td-saturday' : null }} {{ $sun ? 'td-sunday' : null }}" data-item-id="{{ $child->id }}" data-day="{{ $d->toDateString() }}" data-n-events="0"></td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        @endforeach
    @endforeach

    </tbody>
</table>
<textarea id="data-events" style="display: none">{{ $events }}</textarea>
