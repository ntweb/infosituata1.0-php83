@can('can_create_mezzi')
<form class="form-inline">
    <a href="{{ route('mezzi.create') }}" class="btn btn-sm btn-light ml-1"><i class="fa fa-plus"></i> Crea nuovo mezzo</a>
    <div class="dropdown d-inline-block ml-1">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('mezzi.export') }}" class="dropdown-item">
                Export mezzi
            </a>

            <a href="{{ route('carburante.index') }}" class="dropdown-item">
                Export schede carburante
            </a>
        </div>
    </div>
</form>
@endcan

