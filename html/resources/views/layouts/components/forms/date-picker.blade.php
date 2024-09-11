@php
    $class = isset($class) ? $class : 'col-md-12';
    $placeholder = isset($placeholder) ? $placeholder : $slot;

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    $_read_only = isset($_read_only) ? $_read_only : false;

    $_read_only = false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
            <input placeholder="{{ $placeholder }}" type="text" class="form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" value="{{ old($name, $value) }}" autocomplete="off" data-toggle="datepicker" @if($_read_only || isset($force_read_only)) disabled readonly @endif>
            <em class="help-block {{ $_error_hidden }}" role="alert">
                @error($name)
                {{ $message}}
                @enderror
            </em>
        </div>
    </div>
</div>
