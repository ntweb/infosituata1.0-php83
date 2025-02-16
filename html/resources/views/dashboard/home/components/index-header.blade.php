@if(session()->has('fs'))
    <a href="{{ url('/') }}?fs=all" class="btn btn-sm btn-primary ml-1"><i class="fa fa-users"></i> Mostra tutte le scadenze</a>
@else
    <a href="{{ url('/') }}?fs=me" class="btn btn-sm btn-primary ml-1"><i class="fa fa-user"></i> Mostra scadenze personali</a>
@endif
