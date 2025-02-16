@php
    $inline = isset($inline) ? $inline : false;
    $disabled = isset($disabled) ? $disabled : [];

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';
@endphp

<div class="{{ $class }} d-flex flex-column mb-2">
    <label for="" style="">{{ $slot }}</label>
    <div class="position-relative form-group">
        @foreach($elements as $v => $txt)
            @php
                $_disabled = in_array($v, $disabled) ? 'disabled' : null;
            @endphp
            <div class="form-check @if($inline) form-check-inline @endif">
                <input type="radio" name="{{ $name }}" id="{{ $name.'-'.$v }}" value="{{ $v }}" @if($v == old($name, $value) ) checked="checked" @endif {{ $_disabled }} >
                <label class="form-check-label" for="{{ $name.'-'.$v }}">
                    &nbsp; {{ $txt }}
                </label>
            </div>
        @endforeach
        @if(isset($helper))
            <small class="form-text text-muted">{!! $helper !!}</small>
        @endif

        <em class="help-block {{ $_error_hidden }}" role="alert" style="display: block;">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>

</div>
