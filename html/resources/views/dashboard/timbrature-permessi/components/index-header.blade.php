@can('can-create')
<form class="form-inline">
    <a href="javascript:void(0)" class="btn btn-sm btn-light ml-1 createNewPermessoPowerUser" data-route="{{ route('timbrature-permessi.create', ['_power_user' => true]) }}"><i class="fa fa-plus"></i> Crea nuovo permesso</a>
    <a href="javascript:void(0)" class="btn btn-sm btn-light ml-1"  data-toggle="modal" data-target="#modalPermessiSearch"><i class="bx bx-search"></i></a>
</form>
@endcan
