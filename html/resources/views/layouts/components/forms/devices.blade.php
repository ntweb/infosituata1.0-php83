@php
    $class = isset($class) ? $class : 'col-md-12';
    $elements = \App\Models\DeviceType::orderBy('brand')->orderBy('label')->get()->groupBy('brand');

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="multiselect-dropdown form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%">
            <option value="">-- Seleziona un dispositivo --</option>

            @foreach($elements as $brand => $els)
            <optgroup label="{{ Str::title($brand) }}">
                @foreach($els as $el)
                <option value="{{ $el->id }}" @if($el->id == old($name, $value)) selected="selected" @endif>{{ Str::title($el->label) }}</option>
                @endforeach
            </optgroup>
            @endforeach

        </select>
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>
</div>

