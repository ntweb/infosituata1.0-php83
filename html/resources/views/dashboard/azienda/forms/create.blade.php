@php
    $action = isset($el) ? route('azienda.update', [$el->id, '_type' => 'json']) : route('azienda.store');
    $class = isset($el) ? 'ns' : null;
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @if(isset($el))
        @method('PUT')
    @endif

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Anagrafica
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @component('layouts.components.forms.text', ['name' => 'uid', 'value' => @strtoupper($el->uid), 'class' => 'col-md-6', 'maxLength' => 10, '_read_only' => isset($el), 'helper' => 'È un codice univoco aziendale che sarà utilizzato all\'interno dell\'ecosistema di AWS'])
                    UID
                @endcomponent

                <div class="col-12">
                    <hr>
                </div>

                @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                    Ragione sociale
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'citta', 'value' => @$el->citta, 'class' => 'col-md-8'])
                    Città
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'provincia', 'value' => @$el->provincia, 'class' => 'col-md-4'])
                    Provincia
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'cap', 'value' => @$el->cap, 'class' => 'col-md-4'])
                    CAP
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'indirizzo', 'value' => @$el->indirizzo, 'class' => 'col-md-8'])
                    Indirizzo
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'legale_rappresentante', 'value' => @$el->legale_rappresentante, 'class' => 'col-md-12'])
                    Legale rappresentante
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'legale_rappresentante_tel', 'value' => @$el->legale_rappresentante_tel, 'class' => 'col-md-4'])
                    Legale rappresentante TEL
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'legale_rappresentante_email', 'value' => @$el->legale_rappresentante_email, 'class' => 'col-md-4'])
                    Legale rappresentante EMAIL
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'rpd', 'value' => @$el->rpd, 'class' => 'col-md-12'])
                    RPD nome
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'rpd_email', 'value' => @$el->rpd_email, 'class' => 'col-md-6'])
                    RPD EMAIL
                @endcomponent

                @component('layouts.components.forms.text', ['name' => 'email_contatto_privacy', 'value' => @$el->email_contatto_privacy, 'class' => 'col-md-4'])
                    EMAIL contatto privacy
                @endcomponent

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
