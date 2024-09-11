<form class="form-inline d-flex justify-content-end">
    <div class="d-flex justify-content-end">
        <a href="{{ route('commessa.show', $el->id) }}" tabindex="0" class="btn btn-primary">Analisi commessa</a>
        @if(Gate::check('commessa_mod_extra_fields', $el) ||
            Gate::check('commessa_notify_status', $el) ||
            Gate::check('commessa_uploads', $el) ||
            Gate::check('commessa_print', $el) ||
            Gate::check('commessa_mod_autorizzazioni', $el)
        )
            <div class="dropdown ml-1">
                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="btn btn-primary">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
                    @can('commessa_mod_extra_fields', $el)
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#extraFieldModal">Extra field</a>
                    @endcan
                    @can('commessa_notify_status', $el)
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#notificationStatusModal">Notifiche cambiamento stato</a>
                    @endcan
                    @can('commessa_uploads', $el)
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item uploadDocNode" data-route="{{ route('upload-s3.modal', ['reference_id' => $el->id, 'reference_table' => 'commesse']) }}">Upload documenti commessa</a>
                    @endcan
                    @can('commessa_print', $el)
                        <button type="button" tabindex="0" class="dropdown-item" data-toggle="modal" data-target="#printModal">Stampa commessa</button>
                    @endif
                    @can('commessa_mod_autorizzazioni', $el)
                        <div class="divider"></div>

                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#commessaCopyExtraField">Extra field copia da...</a>
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#commessaMassiveCopyItem">Item copia da...</a>

                        <div class="divider"></div>
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item autorizzazioni" data-route="{{ route('commessa-autorizzazioni.index', ['id' => $el->id]) }}">Autorizzazioni</a>
                        <a href="javascript:void(0);" tabindex="1" class="dropdown-item" data-toggle="modal" data-target="#modalAutorizzazioniCopy">Autorizzazioni copia da...</a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</form>
<div class="clearfix"></div>
