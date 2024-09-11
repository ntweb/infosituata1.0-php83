<div class="bg-white" role="region" aria-labelledby="caption" tabindex="0">
    <table class="scheduler">
        <thead>
            <tr>
                <th class="label"></th>
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
            @foreach($items as $group)
                @foreach($group as $item)
                    <tr id="tr-item-{{ $item->id }}">
                        <th class="label" style="">{{ $item->label }}</th>
                        @foreach($period as $d)
                            @php
                                $sat = $d->dayOfWeek === \Carbon\Carbon::SATURDAY;
                                $sun = $d->dayOfWeek === \Carbon\Carbon::SUNDAY;
                            @endphp
                            <td class="day {{ $sat ? 'td-saturday' : null }} {{ $sun ? 'td-sunday' : null }}" data-item-id="{{ $item->id }}" data-day="{{ $d->toDateString() }}" data-n-events="0"></td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
<textarea id="data-events" style="display: none">{{ $events }}</textarea>
