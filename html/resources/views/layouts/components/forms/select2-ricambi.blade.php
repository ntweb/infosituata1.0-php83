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

    $_ricambio = null;
    if (isset($value))
        $_ricambio = \App\Models\Ricambio::find($value);

@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="select2-ricambi-ajax form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%" @if($_read_only) disabled readonly @endif>
            @if($_ricambio)
                <option value="{{ $_ricambio->id }}" selected="selected">{{ $_ricambio->label }}</option>
            @endif
        </select>
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
        <a href="javascript:void(0);" class="btn btn-sm btn-light mt-1 get-html"
            data-container="#drawer"
            data-route="{{ route('ricambi.create') }}"
            data-callback="openDrawer();"><i class="fa fa-plus"></i> Crea nuovo ricambio</a>
    </div>
</div>

