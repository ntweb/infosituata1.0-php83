@php
    $class = isset($class) ? $class : 'col-md-12';
    $elements = [];
    $elementsSelected = isset($elementsSelected) ? $elementsSelected : [1, 2];

    // dump($elementsSelected);

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    $_read_only = isset($_read_only) ? $_read_only : false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;

    $_template = null;
    if (isset($value))
        $_template = \App\Models\CommessaTemplate::find($value);

@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="select2-commesse-ajax form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%" @if($_read_only) disabled readonly @endif data-dropdownParent="{{ $dropdownParent ?? null }}">
            @if($_template)
                <option value="{{ $_template->id }}" selected="selected">{{ $_template->label }}</option>
            @endif
        </select>
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>
</div>

