@php
    $value = null;
    $start = isset($start) ? $start : date("Y-m-d");
    if ($start) {
        $value = date('d/m/Y H:i', strtotime($start));
    }
@endphp
<div class="form-group {{ isset($class) ? $class : 'col-md-12' }} @error( $name ) has-error @enderror">
    <label>@error( $name )<i class="fa fa-times-circle-o"></i>@enderror {{ $label }}</label>
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
        </div>
        <input type="text" class="form-control dateTimePickerSingle" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" value="{{ old($name, $value) }}" autocomplete="off">
    </div>
    @error( $name )
    <span class="help-block" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    @if(isset($help))
    <p class="help-block">{!! $help !!}</p>
    @endif
</div>
