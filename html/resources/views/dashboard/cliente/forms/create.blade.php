<div class="row">
    <div class="col-md-12">

        @component('layouts.components.forms.fieldset', ['title' => 'Dati aziendali'])
            <div class="row">

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="fl_soggetto_privato" name="fl_soggetto_privato" @if(@$el->fl_soggetto_privato) checked @endif>
                        <label class="form-check-label" for="fl_soggetto_privato">
                            Soggetto privato
                        </label>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="fl_persona_fisica" name="fl_persona_fisica" @if(@$el->fl_persona_fisica) checked @endif>
                        <label class="form-check-label" for="fl_persona_fisica">
                            Persona fisica
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.text', ['name' => 'rs', 'value' => isset($el) ? $el->rs : null, 'class' => 'col-md-12'])
                    Ragione sociale / Denominazione
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'nome', 'value' => isset($el) ? $el->nome : null, 'class' => 'col-md-4'])
                    Nome
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'cognome', 'value' => isset($el) ? $el->cognome : null, 'class' => 'col-md-4'])
                    Cognome
                @endcomponent

                @component('layouts.components.forms.date-native', ['name' => 'data_nascita',  'value' => @$el->data_nascita, 'class' => 'col-md-4'])
                    Data di nascita
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'piva', 'value' => isset($el) ? $el->piva : null, 'class' => 'col-md-6'])
                    Partita IVA
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'cf', 'value' => isset($el) ? $el->cf : null, 'class' => 'col-md-6'])
                    Codice fiscale
                @endcomponent
            </div>
        @endcomponent

        <div class="mb-2"></div>

        @component('layouts.components.forms.fieldset', ['title' => 'Indirizzo'])
            <div class="row">
                @component('layouts.components.forms.text', ['name' => 'indirizzo', 'value' => isset($el) ? $el->indirizzo : null, 'class' => 'col-md-12'])
                    Indirizzo
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'cap', 'value' => isset($el) ? $el->cap : null, 'class' => 'col-md-3'])
                    CAP
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'citta', 'value' => isset($el) ? $el->citta : null, 'class' => 'col-md-6'])
                    Città
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'provincia', 'value' => isset($el) ? $el->provincia : null, 'class' => 'col-md-3'])
                    Prov
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'telefono', 'value' => isset($el) ? $el->telefono : null, 'class' => 'col-md-12'])
                    Telefono
                @endcomponent
            </div>
        @endcomponent

        <div class="mb-2"></div>

        @component('layouts.components.forms.fieldset', ['title' => 'Dati generali'])
            <div class="row">

                @component('layouts.components.forms.select', ['name' => 'res_fiscale', 'value' => @$el->res_fiscale, 'elements' => ['italia' => 'Italia', 'cee' => 'CEE', 'estero' => 'Estero', 'rsm' => 'RSM', 'VATICANO' => 'Vaticano'], 'class' => 'col-md-4'])
                    Residenza fiscale
                @endcomponent

                @component('layouts.components.forms.select-stati', ['name' => 'stato', 'value' => @$el->stato, 'class' => 'col-md-4'])
                    Paese
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'telefono', 'value' => isset($el) ? $el->telefono : null, 'class' => 'col-md-4'])
                    Telefono
                @endcomponent

            </div>
        @endcomponent

        <div class="mb-2"></div>

        @component('layouts.components.forms.fieldset', ['title' => 'Dati fatt. elettronica'])
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="fl_ente_pubblico" name="fl_ente_pubblico" @if(@$el->fl_ente_pubblico) checked @endif>
                        <label class="form-check-label" for="fl_ente_pubblico">
                            Ente pubblico
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.select', ['name' => 'tipo_fattura', 'value' => @$el->tipo_fattura, 'elements' => ['b2b' => 'Fattura B2B', 'pa' => 'Fattura PA'], 'class' => 'col-md-4'])
                    Tipo fatt. elettronica
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'sdi', 'value' => isset($el) ? $el->sdi : null, 'class' => 'col-md-4'])
                    Cod. destinatario SDI
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'pec', 'value' => isset($el) ? $el->pec : null, 'class' => 'col-md-4'])
                    Pec
                @endcomponent
            </div>
        @endcomponent

        <div class="mb-2"></div>

        @component('layouts.components.forms.fieldset', ['title' => 'Altri dati contabili'])
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="fl_addebito_bollo" name="fl_addebito_bollo" @if(@$el->fl_addebito_bollo) checked @endif>
                        <label class="form-check-label" for="fl_addebito_bollo">
                            Addebito bollo
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="fl_split_payment" name="fl_split_payment" @if(@$el->fl_split_payment) checked @endif>
                        <label class="form-check-label" for="fl_split_payment">
                            Altri soggetti Split payment
                        </label>
                    </div>
                </div>

                @component('layouts.components.forms.date-native', ['name' => 'fl_split_payment_da_data',  'value' => @$el->fl_split_payment_da_data, 'class' => 'col-md-6'])
                    Gestione Split payment da
                @endcomponent

            </div>
        @endcomponent
    </div>

</div>
