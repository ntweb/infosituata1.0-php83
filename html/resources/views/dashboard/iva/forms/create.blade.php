<div class="row">
    <div class="col-md-12">

        <div class="row">


            @component('layouts.components.forms.text', ['name' => 'codice', 'value' => isset($el) ? $el->codice : null, 'class' => 'col-md-6'])
                Codice
            @endcomponent

            @component('layouts.components.forms.text', ['name' => 'natura', 'value' => isset($el) ? $el->natura : null, 'class' => 'col-md-3'])
                Natura
            @endcomponent

            @component('layouts.components.forms.number', ['name' => 'iva', 'value' => @$el->iva ? $el->iva : 0.00, 'class' => 'col-md-3', 'min' => 0, 'step' => 0.01])
                IVA %
            @endcomponent

            @component('layouts.components.forms.textarea', ['name' => 'descrizione', 'value' => isset($el) ? $el->descrizione : null, 'class' => 'col-md-12'])
                Descrizione
            @endcomponent

            @component('layouts.components.forms.textarea', ['name' => 'descrizione_estesa', 'value' => isset($el) ? $el->descrizione_estesa : null, 'class' => 'col-md-12'])
                Descrizione estesa
            @endcomponent

            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="fl_esenzione" name="fl_esenzione" @if(@$el->fl_esenzione) checked @endif>
                    <label class="form-check-label" for="fl_esenzione">
                        Esenzione
                    </label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="fl_spese_bollo" name="fl_spese_bollo" @if(@$el->fl_spese_bollo) checked @endif>
                    <label class="form-check-label" for="fl_spese_bollo">
                        Spese di bollo
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>
