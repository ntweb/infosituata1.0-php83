@php
        $class = isset($class) ? $class : 'col-md-12';
        $placeholder = isset($placeholder) ? $placeholder : $slot;

        $_error_display = $errors->first($name) ? true : false;
        $_error_class = $_error_display ? 'is-invalid' : null;
        $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
        $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

        $accept = isset($accept) ? $accept : null;
@endphp


@component('layouts.components.forms.text', ['name' => 'label', 'value' => null, 'class' => $class])
    Etichetta
@endcomponent

<div class="{{ $class }}">

    <div class="position-relative form-group">
        <label for="{{ isset($id) ? $id : $name }}" class="">{{ $placeholder }}</label>
        <input  name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" type="file" class="form-control-file {{  $_error_class }} ajaxFormTriggerInput" value="{{ old($name, $value) }}" @if($accept) accept="{{ $accept }}" @endif>
        @if(isset($help))
            <small class="form-text text-muted">
                {!! $help !!}
            </small>
        @endif
        <em class="help-block {{ $_error_hidden }} ajaxFormTriggerError" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>
    <div class="mb-3 progress-bar-animated-alt progress progress-bar-xs">
        <div class="progress-bar bg-info ajaxFormProgress" id="{{ isset($id) ? $id : $name }}-progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>
</div>
