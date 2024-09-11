@if(Auth::user()->superadmin)
    @component('layouts.components.forms.aziende', ['name' => 'azienda_id', 'value' => isset($azienda_id) ? $azienda_id : @$el->azienda_id, 'class' => 'col-md-6'])
        Azienda
    @endcomponent
    <div class="col-md-12"></div>
@endif

@component('layouts.components.forms.text', ['name' => 'extras1', 'value' => @$el->extras1, 'class' => 'col-md-6'])
    Etichetta
@endcomponent

@component('layouts.components.forms.text', ['name' => 'extras3', 'value' => @$el->extras3, 'class' => 'col-md-6'])
    Codice / Matricola
@endcomponent

<input name="active" type="hidden" value="0" />
@component('layouts.components.forms.toggle', ['name' => 'active', 'value' => @$el->active, 'class' => 'col-md-6', 'toggle' => ['1' => 'Attivato', '0' => 'Disattivato'] ])
    Stato di attivazione
@endcomponent

@component('layouts.components.forms.select2-clienti', ['name' => 'clienti_id', 'value' => @$el->clienti_id, 'class' => 'col-md-12'])
    Fornitore
@endcomponent

<input name="fl_external" type="hidden" value="0" />
@component('layouts.components.forms.toggle', ['name' => 'fl_external', 'value' => @$el->fl_external, 'class' => 'col-md-12', 'toggle' => ['1' => 'Esterno', '0' => 'Interno'] ])
    Attrezzatura esterna all'azienda e/o in rent
@endcomponent

<div class="col-12"><hr></div>
@component('layouts.components.forms.tags', ['name' => 'tags', 'value' => @$el->tags, 'class' => 'col-md-12'])
    Tags
@endcomponent


<div class="col d-none error-box">
    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
        Errore in fase di salvataggio
    @endcomponent
</div>
