@can('can_create_materiali')
<form class="form-inline">
    <div class="dropdown d-inline-block">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('scadenzario.create') }}?id={{ md5($el->id) }}" class="dropdown-item">
                Crea nuova scadenza
            </a>
        </div>
    </div>
</form>
@endcan
