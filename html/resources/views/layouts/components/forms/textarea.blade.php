@php
    $class = isset($class) ? $class : 'col-md-12';
    $placeholder = isset($placeholder) ? $placeholder : $slot;

    $maxLength = isset($maxLength) ? $maxLength : 250;

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    $_read_only = isset($_read_only) ? $_read_only : false;

    $_counter = isset($counter) ? $counter : false;
    $_counter_class = $_counter ? ' enable-counter ' : '';
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <textarea placeholder="{{ $placeholder }}" class="form-control {{ $_counter_class }} {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" maxlength="{{ $maxLength }}" @if($_read_only) disabled readonly @endif rows="5">{{ old($name, $value) }}</textarea>
        @if($_counter)
            <div class="mt-1">
                <span>Lunghezza testo: </span><span id="{{ isset($id) ? $id : $name }}_counter" class="char_counter font-weight-bold" data-selector="#{{ isset($id) ? $id : $name }}"></span>
            </div>
        @endif
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
