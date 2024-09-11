<div class="dropdown d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Menù</button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
        <h6 tabindex="-1" class="dropdown-header">Azienda</h6>
        <a href="{{ route('sede.create') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-building mr-2"></i> Sedi</a>
        <a href="{{ route('gruppo.create') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-users mr-2"></i> Gruppi</a>
        <a href="{{ route('user.create') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-user mr-2"></i> Utenti</a>

        <h6 tabindex="-1" class="dropdown-header">Terminali</h6>
        <a href="{{ route('device.index') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-mobile mr-2"></i> Elenco terminali</a>
        <a href="{{ route('device.configuration') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-cog mr-2"></i> Configurazione globale</a>

        <h6 tabindex="-1" class="dropdown-header">Tipologie scadenze</h6>
        <a href="{{ route('tipologia-scadenza.index') }}?azienda={{ $el->id }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-list-alt mr-2"></i> Elenco</a>
    </div>
</div>
