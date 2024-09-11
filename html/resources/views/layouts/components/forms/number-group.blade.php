@php
    $class = isset($class) ? $class : 'col-md-12';
    $placeholder = isset($placeholder) ? $placeholder : $slot;
    $step = isset($step) ? $step : 'any';
    $min = isset($min) ? $min : null;
    $max = isset($max) ? $max : null;

    $group_text = isset($group_text) ? $group_text : 'nd';
    $group_align = isset($group_align) ? $group_align : 'left';

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    $_read_only = false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;

@endphp

<div class="{{ $class }}" >
    <label for="exampleEmail11" class="">{{ $slot }}</label>
    <div class="position-relative input-group">
        @if($group_align == 'left')
            <div class="input-group-prepend"><span class="input-group-text">{!! $group_text !!}</span></div>
        @endif
        <input placeholder="{{ $placeholder }}" type="number" class="form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" value="{{ old($name, $value) }}" autocomplete="off" step="{{ $step }}" @if($min || $min == 0) min="{{ $min }}" @endif @if($max) max="{{ $max }}" @endif @if($_read_only) disabled readonly @endif>
        @if($group_align == 'right')
            <div class="input-group-append"><span class="input-group-text">{!! $group_text !!}</span></div>
        @endif
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>
</div>
