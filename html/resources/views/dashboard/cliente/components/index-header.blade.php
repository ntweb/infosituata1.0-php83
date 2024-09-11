<form class="form-inline">

    @can('can_create_clienti')
        <a href="javascript:void(0)" data-route="{{ route('cliente.create') }}" class="btn btn-sm btn-light ml-1 btnEditCliente"><i class="fa fa-plus"></i> Crea nuovo cliente</a>
    @endcan

    <a href="javascript:void(0)" class="btn btn-sm btn-light ml-1"  data-toggle="modal" data-target="#modalClientiSearch"><i class="bx bx-search"></i></a>

    @can('can_create_clienti')
        <div class="dropdown d-inline-block ml-1">
            <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Funzioni</button>
            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                <a href="{{ route('cliente.import') }}" class="dropdown-item">
                    Import clienti
                </a>
                <a href="{{ route('cliente.export') }}" class="dropdown-item">
                    Export clienti
                </a>
            </div>
        </div>
    @endcan

</form>
