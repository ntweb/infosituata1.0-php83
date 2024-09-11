@php
    $class = isset($class) ? $class : 'col-md-12';
    $placeholder = isset($placeholder) ? $placeholder : $slot;

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <input placeholder="{{ $placeholder }}" type="text" class="form-control tags {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" value="{{ old($name, $value) }}" autocomplete="off">
        <em class="help-block {{ $_error_hidden }}" role="alert">
        @error($name)
            {{ $message}}
        @enderror
        </em>
        @if(isset($helper))
            <small class="form-text text-muted">{!! $helper !!}</small>
        @endif
    </div>
</div>
