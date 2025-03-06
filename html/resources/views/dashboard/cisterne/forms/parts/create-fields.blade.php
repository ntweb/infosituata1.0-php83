@component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-8'])
    Etichetta
@endcomponent

@component('layouts.components.forms.number', ['name' => 'livello_attuale', 'value' => @$el->livello_attuale ? $el->livello_attuale : 0.00, 'class' => 'col-md-4', 'min' => 0, 'step' => 0.01])
    Livello attuale
@endcomponent

@component('layouts.components.forms.select2-multiple', ['name' => 'gruppi_ids', 'value' => 'vediamo', 'class' => 'col-md-8', 'elements' => $gruppi, 'elementsSelected' => $gruppiSel, 'check_permission' => true])
    Avvisa i seguenti gruppi broadcast
@endcomponent

@component('layouts.components.forms.number', ['name' => 'livello_minimo', 'value' => @$el->livello_minimo ? $el->livello_minimo : 0.00, 'class' => 'col-md-4', 'min' => 0, 'step' => 0.01])
    Livello minimo
@endcomponent


<div class="col d-none error-box">
    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
        Errore in fase di salvataggio
    @endcomponent
</div>
