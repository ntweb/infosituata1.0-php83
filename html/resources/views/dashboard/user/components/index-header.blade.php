@can('can_create_utenti')
<form class="form-inline">
    <a href="{{ route('user.create') }}" class="btn btn-sm btn-light ml-1"><i class="fa fa-plus"></i> Crea nuovo utente</a>
    <div class="dropdown d-inline-block ml-1">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('user.export') }}" class="dropdown-item">
                Export utenti
            </a>
        </div>
    </div>
</form>
@endcan
