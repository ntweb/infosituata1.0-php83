@can('can_create_utenti')
<form class="form-inline pull-right">
    <div class="dropdown">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('scadenzario.create') }}?id={{ md5($el->id) }}" tabindex="0" class="dropdown-item"><i class="far fa-fw fa-plus-square mr-2"></i> Crea nuova scadenza</a>
            <div tabindex="-1" class="dropdown-divider"></div>
            <a href="{{ route('infosituata.log', [$el->id]) }}" tabindex="0" class="dropdown-item"><i class="far fa-list-alt mr-2"></i> Log visualizzazione risorse</a>
        </div>
    </div>
</form>
<div class="clearfix"></div>
@endcan
