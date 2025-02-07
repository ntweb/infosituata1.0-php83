@php
    $inline = isset($inline) ? $inline : false;
    $disabled = isset($disabled) ? $disabled : [];
    $parentesiQuadre = count($elements) > 1 ? '[]' : null;

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';

    if (isset($slot))
        $label = $slot;

    if (old($name)) {
        if (is_array(old($name))) {
            $_old_values = array_combine(old($name, []), old($name, []));
        } else {
            $_old_values = array_combine([], []);
        }
    }
@endphp

<div class="form-group {{ isset($class) ? $class : 'col-md-12' }} @error( $name ) has-error has-error-checkboxes @enderror">
    @if(isset($label))
        <label>@error( $name )<i class="fa fa-times-circle-o"></i>@enderror {{ $label }}</label>
        @if($inline)
            <br>
        @endif
    @endif

    @foreach($elements as $v => $txt)
        @php
            $_disabled = in_array($v, $disabled) ? 'disabled' : null;
        @endphp

        @if(!$inline)
        <div class="checkbox {{ $_disabled }}">
        @endif
            <label @if($inline) class="checkbox-inline {{ $_disabled }} mr-3" @endif>
                <input type="checkbox" name="{{ $name }}{{ $parentesiQuadre }}" id="{{ $name.'-'.$v }}" value="{{ $v }}" @if($v == old($name, $value) || isset($value[$v]) || isset($_old_values[$v]) ) checked="checked" @endif {{ $_disabled }} >
                {{ $txt }}
            </label>
        @if(!$inline)
        </div>
        @endif
    @endforeach

    @error( $name )
    <span class="help-block text-danger" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    <em class="help-block {{ $_error_hidden }}" role="alert" style="display: block;">
        @error($name)
        {{ $message}}
        @enderror
    </em>

    @if(isset($help))
    <p class="help-block">{!! $help !!}</p>
    @endif

    @if(isset($helper))
        <small class="form-text text-muted">{!! $helper !!}</small>
    @endif


</div>

