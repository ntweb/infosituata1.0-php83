@php
    $class = isset($class) ? $class : 'col-md-12';
    $placeholder = isset($placeholder) ? $placeholder : $slot;
    $icon = isset($icon) ? $icon : 'fa fa-phone';
@endphp

<div class="{{ $class }} @error( $name ) has-error @enderror">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="{{ $icon }}"></i></span></div>
            <input placeholder="{{ $placeholder }}" type="text" class="form-control" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" value="{{ old($name, $value) }}" autocomplete="off" >
        </div>
    </div>
</div>
