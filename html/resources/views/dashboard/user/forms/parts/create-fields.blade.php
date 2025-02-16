@if(Auth::user()->superadmin)
    @component('layouts.components.forms.aziende', ['name' => 'azienda_id', 'value' => isset($azienda_id) ? $azienda_id : @$el->azienda_id, 'class' => 'col-md-6'])
        Azienda
    @endcomponent
    <div class="col-md-12"></div>
@endif

@component('layouts.components.forms.text', ['name' => 'extras1', 'value' => @$el->extras1, 'class' => 'col-md-6'])
    Cognome
@endcomponent

@component('layouts.components.forms.text', ['name' => 'extras2', 'value' => @$el->extras2, 'class' => 'col-md-6'])
    Nome
@endcomponent

@component('layouts.components.forms.text', ['name' => 'extras3', 'value' => @$el->extras3, 'class' => 'col-md-6'])
    Matricola
@endcomponent

@component('layouts.components.forms.text', ['name' => 'extras5', 'value' => @$el->extras5, 'class' => 'col-md-3', 'helper' => 'Attenzione: il prefisso è obbligatorio se si intende inviare messaggi automatici tramite whatsapp'])
    Pref. internazionale
@endcomponent

@component('layouts.components.forms.text', ['name' => 'extras4', 'value' => @$el->extras4, 'class' => 'col-md-3'])
    Telefono
@endcomponent

<div class="col-12"><hr></div>

@component('layouts.components.forms.text', ['name' => 'user_luogo_nascita', 'value' => @$el->user_luogo_nascita, 'class' => 'col-md-6'])
    Nato a
@endcomponent

@component('layouts.components.forms.date-native', ['name' => 'user_data_nascita', 'id' => 'user_data_nascita',  'value' => @$el->user_data_nascita, 'class' => 'col-md-6'])
    Nato il
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_luogo_residenza', 'value' => @$el->user_luogo_residenza, 'class' => 'col-md-4'])
    Residente a
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_via_residenza', 'value' => @$el->user_via_residenza, 'class' => 'col-md-8'])
    Via
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_telefono', 'value' => @$el->user_telefono, 'class' => 'col-md-6'])
    Telefono
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_cellulare', 'value' => @$el->user_cellulare, 'class' => 'col-md-6'])
    Cellulare
@endcomponent

@component('layouts.components.forms.date-native', ['name' => 'user_data_assunzione', 'id' => 'user_data_assunzione',  'value' => @$el->user_data_assunzione, 'class' => 'col-md-4'])
    Data assunzione
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_qualifica_assunzione', 'value' => @$el->user_qualifica_assunzione, 'class' => 'col-md-8'])
    Qualifica assunzione
@endcomponent

@component('layouts.components.forms.text', ['name' => 'user_titolo_studio', 'value' => @$el->user_titolo_studio, 'class' => 'col-md-12'])
    Titolo di studio
@endcomponent

<div class="col-12"><hr></div>

<input name="fl_external" type="hidden" value="0" />
@component('layouts.components.forms.toggle', ['name' => 'fl_external', 'value' => @$el->fl_external, 'class' => 'col-md-12', 'toggle' => ['1' => 'Esterno', '0' => 'Interno'] ])
    Consulente e/o lavoratore esterno all'azienda
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

