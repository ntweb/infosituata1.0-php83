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
        @foreach($nodes as $node)
        <tr id="tr-item-{{ $node->id }}">
            <th class="label" style="line-height: 12px;">
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
                <tr id="tr-item-{{ $child->id }}">
                    <th class="label pl-4" style="line-height: 12px;">
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
    </tbody>
</table>
<textarea id="data-events" style="display: none">{{ $events }}</textarea>
