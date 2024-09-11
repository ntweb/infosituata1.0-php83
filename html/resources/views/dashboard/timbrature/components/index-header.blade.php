<div class="d-flex align-items-center">
    <a href="{{ route('timbrature.create', ['_admin' => true]) }}" class="btn btn-sm btn-light mr-1">
        <i class="fa fa-plus"></i>
        Crea nuova timbratura
    </a>

    <form class="form-inline" action="{{ route('timbrature.index') }}">

        <div class="d-flex align-items-center">

            @component('layouts.components.forms.date-native', ['name' => 'dateTimbrature', 'value' => $date, 'class' => 'col'])
            @endcomponent

            @if($list->count())
                <a class="btn btn-info" href="{{ route('timbrature.index', ['dateTimbrature' => $date, 'export' => 1]) }}">export</a>
            @endif
        </div>
    </form>
</div>
