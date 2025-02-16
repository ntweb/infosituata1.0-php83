@if($timbratura->type == 'in')
    <span class="badge badge-success">
        {{ ora($timbratura->marked_at) }} Ingresso
    </span>
@else
    <span class="badge badge-danger">
        {{ ora($timbratura->marked_at) }} Uscita
    </span>
@endif
