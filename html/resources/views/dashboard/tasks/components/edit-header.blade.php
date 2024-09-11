<form class="form-inline d-flex justify-content-end">
    <div class="d-flex justify-content-end">

        @if(Gate::check('task_mod_extra_fields', $el) ||
            Gate::check('task_notify_status', $el) ||
            Gate::check('task_print', $el) ||
            Gate::check('task_mod_autorizzazioni', $el)
        )
            <div class="dropdown ml-1">
                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="btn btn-primary">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                    @can('task_mod_autorizzazioni', $el)
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item autorizzazioni" data-route="{{ route('task-autorizzazioni.index', ['id' => $el->id]) }}">Autorizzazioni</a>
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#modalAutorizzazioniCopy">Autorizzazioni copia da...</a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</form>
<div class="clearfix"></div>
