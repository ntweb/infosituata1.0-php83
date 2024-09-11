@php

    $value = old($name, $value);
    if ($value) {
        $comune = \App\Models\Comune::whereCodiceComune($value)->first();
        $option = $comune->comune.' \ '.$comune->regione;
    }

@endphp
<div class="form-group {{ isset($class) ? $class : 'col-md-12' }} @error( $name ) has-error @enderror">
    <label>@error( $name )<i class="fa fa-times-circle-o"></i>@enderror {{ $label }}</label>
    <select class="form-control select2-comuni" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%">
        @if($value)
        <option value="{{ $value }}" selected>{{ $option }}</option>
        @endif
    </select>
    @error( $name )
    <span class="help-block" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    @if(isset($help))
    <p class="help-block">{!! $help !!}</p>
    @endif
</div>
<div class="clearfix"></div>
