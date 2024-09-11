<div class="row">
    <div class="col px-4">
        <dl class="row">
            <dt class="col-sm-4 mb-1 border font-weight-bold">Fase / sottofase di riferimento</dt>
            <dd class="col-sm-8 mb-1 border">{{ Str::title($el->fase->label) }}</dd>
            <dt class="col-sm-4 mb-1 border font-weight-bold">Data di riferimento</dt>
            <dd class="col-sm-8 mb-1 border">{{ date($el->start) }}</dd>
            <dt class="col-sm-4 mb-1 border font-weight-bold">Redatto da</dt>
            <dd class="col-sm-8 mb-1 border">{{ Str::title($el->username) }}</dd>
            <dt class="col-sm-4 mb-1 border font-weight-bold">Redatto Il</dt>
            <dd class="col-sm-8 mb-1 border">{{ dataOra($el->created_at) }}</dd>
            <dt class="col-sm-4 mb-1 border font-weight-bold">Livello priorità</dt>
            <dd class="col-sm-8 mb-1 border">
                @component('dashboard.commesse.components.labels.rapportino-livello', ['el' => $el])
                @endcomponent
            </dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-4 mb-1 border font-weight-bold">Oggetto</dt>
            <dd class="col-sm-8 mb-1 border">{{ Str::title($el->titolo) }}</dd>
            <dt class="col-sm-12 mb-1 border font-weight-bold">Descrizione</dt>
            <dd class="col-sm-12 mb-1 border">{!! nl2br($el->descrizione) !!}</dd>
        </dl>
    </div>
</div>
