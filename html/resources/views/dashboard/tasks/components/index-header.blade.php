@can('can_create_tasks')
<form class="form-inline d-flex justify-content-end">
    <div class="d-flex justify-content-end">
        <div class="dropdown ml-1">
            <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="btn btn-sm btn-light ml-1">
                <i class="bx bx-plus"></i> Crea task
            </button>
            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                <a href="{{ route('task.create') }}?_module=fast" tabindex="1" class="dropdown-item">Crea task veloce</a>
                <a href="{{ route('task.create') }}" tabindex="2" class="dropdown-item">Crea task da template</a>
            </div>
        </div>
    </div>
    <a href="javascript:void(0)" class="btn btn-sm btn-light ml-1"  data-toggle="modal" data-target="#modalTasksSearch"><i class="bx bx-search"></i></a>
</form>
<div class="clearfix"></div>
@endcan
