@can('can_create_sms')
<form class="form-inline">
    <a href="{{ route('sms.create') }}" class="btn btn-sm btn-light ml-1"><i class="fa fa-plus"></i> Crea nuovo sms</a>
    <div class="dropdown d-inline-block ml-1">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
            <a href="{{ route('sms.configure') }}" class="dropdown-item">
                Configurazione Sms
            </a>
        </div>
    </div>
</form>
@endcan
