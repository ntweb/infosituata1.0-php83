<div class="d-flex align-items-center">

    <form class="form-inline" action="{{ route('timbrature.mensili') }}">

        <div class="d-flex align-items-center">

            @component('layouts.components.forms.month-native', ['name' => 'dateTimbratureMese', 'value' => $date, 'class' => 'col'])
            @endcomponent

            @if($list->count())
                <a class="btn btn-info" href="{{ route('timbrature.mensili', ['dateTimbratureMese' => $date, 'export' => 1]) }}">export</a>
            @endif
        </div>
    </form>
</div>
