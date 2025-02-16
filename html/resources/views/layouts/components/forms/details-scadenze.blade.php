@php
    $class = isset($class) ? $class : 'col-md-12';

    // Elementi personalizzati dal cliente
    $elementsPers = \App\Models\InfosituataModuleDetailScadenza::whereInfosituataModuliDetailsId($infosituata_moduli_details_id)->whereAziendaId($azienda_id)->orderBy('label')->get();

    // Elementi preimpostati da Infosituata
    $elements = \App\Models\InfosituataModuleDetailScadenza::whereInfosituataModuliDetailsId($infosituata_moduli_details_id)->whereAziendaId(0)->orderBy('label')->get();

    $elementsPre = [];
    if (count($elementsPers) <= 0 && count($elements) <= 0)
        $elements[] = (object) ['id' => 0, 'label' => 'Non definita'];
    else
        $elementsPre[] = (object) ['id' => '', 'label' => 'Selezionare una voce'];

    $route = isset($route) ? $route : '#';
    $is_checked = $is_checked ? true : false;

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    $_read_only = false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>

        @if (!$is_checked)
            @if(!$_read_only)
                <a href="javascript:void(0)" class="mr-2 btn btn-link btn-sm pull-right btnCreateNewScadenza" data-route="{{ $route }}">[ crea nuova tipologia ]</a>
            @endif
        @endif

        <select class="multiselect-dropdown form-control selDetailScadenze {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%" @if($_read_only) disabled readonly @endif>

            @foreach($elementsPre as $el)
                <option value="{{ $el->id }}" @if($el->id == old($name, $value)) selected="selected" @endif data-id="{{ @$el->id }}" data-m="{{ @$el->mesi ? $el->mesi : 0 }}"  data-g="{{ @$el->giorni ? $el->giorni : 0 }}">{{ $el->label }}</option>
            @endforeach

            @if(count($elementsPers))
                <optgroup label="Scadenze personalizzate">
                    @foreach($elementsPers as $el)
                        <option value="{{ $el->id }}" @if($el->id == old($name, $value)) selected="selected" @endif data-id="{{ @$el->id }}" data-m="{{ @$el->mesi ? $el->mesi : 0 }}" data-g="{{ @$el->giorni ? $el->giorni : 0 }}">{{ $el->label }}</option>
                    @endforeach
                </optgroup>
            @endif

            @if(count($elements))
                <optgroup label="Scadenze di sistema">
                    @foreach($elements as $el)
                        <option value="{{ $el->id }}" @if($el->id == old($name, $value)) selected="selected" @endif data-id="{{ @$el->id }}" data-m="{{ @$el->mesi ? $el->mesi : 0 }}">{{ $el->label }}</option>
                    @endforeach
                </optgroup>
            @endif

        </select>
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>

        @foreach($elements as $el)
            @if(@$el->description)
                <small class="form-text text-muted selDetailScadenzeDescription selDetailScadenzeDescription-{{ $el->id }}" style="display: none">{{ $el->description }}</small>
            @endif
            @if(@$el->mesi)
                <small class="form-text text-muted selDetailScadenzeDescription selDetailScadenzeDescription-{{ $el->id }}" style="display: none">Scade ogni: {{ $el->mesi }} mesi</small>
            @endif
        @endforeach
    </div>
</div>

