@switch($permesso->status)
    @case('accettato')
        <span class="badge badge-success">Accettato</span>
        @break

    @case('rifiutato')
        <span class="badge badge-danger">Rifiutato</span>
        @break

    @default
        <span class="badge badge-warning">In attesa di controllo</span>
@endswitch
