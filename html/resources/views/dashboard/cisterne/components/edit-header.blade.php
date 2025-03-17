@can('can_create_mezzi')
<form class="form-inline">
    <div class="dropdown d-inline-block ml-1">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('cisterne.carico-export', $el->id) }}" class="dropdown-item">
                Export carichi
            </a>
        </div>
    </div>
</form>
@endcan

