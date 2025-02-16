<div class="row">
    <div class="col px-4">
        <dl>
            <dt>Fase / sottofase di riferimento</dt>
            <dd>{{ Str::title($el->fase->label) }}</dd>
            <dt>Data di riferimento</dt>
            <dd>{{ date($el->start) }}</dd>
            <dt>Redatto da</dt>
            <dd>{{ Str::title($el->username) }}</dd>
            <dt>Redatto Il</dt>
            <dd>{{ dataOra($el->created_at) }}</dd>
            <dt>Livello priorità</dt>
            <dd>
                @component('dashboard.commesse.components.labels.rapportino-livello', ['el' => $el])
                @endcomponent
            </dd>
        </dl>
        <hr>
        <dl>
            <dt>Oggetto</dt>
            <dd>{{ Str::title($el->titolo) }}</dd>
            <dt>Descrizione</dt>
            <dd>{!! nl2br($el->descrizione) !!}</dd>
        </dl>
    </div>
</div>
