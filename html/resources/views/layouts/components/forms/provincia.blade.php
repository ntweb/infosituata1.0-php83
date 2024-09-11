@php
    $province = DB::table('v_province')->get();
@endphp

<div class="form-group {{ isset($class) ? $class : 'col-md-12' }} @error( $name ) has-error @enderror">
    <label>@error( $name )<i class="fa fa-times-circle-o"></i>@enderror {{ $label }}</label>
    <select class="form-control select2Classic select-provincia" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%">
        <option value="">-</option>
        @foreach($province as $p)
            <option value="{{ $p->id }}" data-regione="{{ $p->regione }}" data-codice-regione="{{ $p->codice_regione }}" @if($p->id == old($name, $value)) selected="selected" @endif>{{ $p->label }}</option>
        @endforeach
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

<div class="form-group {{ isset($class) ? $class : 'col-md-12' }} @error( $name ) has-error @enderror">
    <label class="control-label">&nbsp</label>
    <p class="form-control-static {{ $name.'-regione' }}">-</p>
</div>

<div class="clearfix"></div>
