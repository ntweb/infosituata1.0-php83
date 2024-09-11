<form class="form-inline">
    <div class="dropdown d-inline-block">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            @can('can_create_attrezzature')
                <a href="{{ route('scadenzario.create') }}?id={{ md5($el->id) }}" class="dropdown-item">
                    Crea nuova scadenza
                </a>
            @endcan
            @can('can_create_manutenzione_attrezzature')
            <a href="{{ route('manutenzione.index', ['id' => $el->id]) }}" class="dropdown-item">
                Manutenzioni
            </a>
            @endcan
            @can('can_create_controllo_attrezzature')
            <a href="{{ route('controllo.index', ['id' => $el->id]) }}" class="dropdown-item">
                Controlli
            </a>
            @endcan
        </div>
    </div>
</form>
