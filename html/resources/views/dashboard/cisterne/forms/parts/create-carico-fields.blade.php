@component('layouts.components.forms.number', ['name' => 'litri', 'value' => 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
    Carico in litri
@endcomponent

@component('layouts.components.forms.number', ['name' => 'prezzo', 'value' => 0.00, 'class' => 'col-md-6', 'min' => 0, 'step' => 0.01])
    Prezzo al litro
@endcomponent

@component('layouts.components.forms.textarea', ['name' => 'note', 'value' => '', 'class' => 'col-md-12', 'maxLength' => 5000])
    Note
@endcomponent
