@can('can_create_risorse')
<form class="form-inline">
    <a href="{{ route('scadenzario.create') }}?id={{ md5($el->id) }}" class="btn btn-sm btn-light ml-1 btn-block"><i class="fa fa-plus"></i> Crea nuova scadenza</a>
</form>
@endcan
