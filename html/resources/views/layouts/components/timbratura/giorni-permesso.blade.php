@switch($permesso->type)
    @case ('malattia')
    @case ('ferie')
        <span>{{ data($permesso->start_at) }} - {{ data($permesso->end_at) }}</span>
        @break

    @case ('permesso giornaliero')
        <span>{{ data($permesso->start_at) }}</span>
        @break

    @case ('permesso orario')
        <span>{{ dataOra($permesso->start_at) }} - {{ dataOra($permesso->end_at) }}</span>
        @break
@endswitch
