@php
    $class = isset($class) ? $class : 'col-md-12';
    $elements = \App\Models\InfosituataModuleDetail::with('module')->orderBy('label')->get();

    $_error_display = $errors->first($name) ? true : false;
    $_error_class = $_error_display ? 'is-invalid' : null;
    $_error_trigger = 'error invalid-feedback trig-error trig-error-'.$name;
    $_error_hidden = $_error_display ? $_error_trigger : $_error_trigger.' hidden';
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="multiselect-dropdown form-control {{  $_error_class }}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" style="width: 100%">
            @foreach($elements as $el)
            <option value="{{ $el->id }}" @if($el->id == old($name, $value)) selected="selected" @endif>{{ $el->module->label }} / {{ $el->label }}</option>
            @endforeach
        </select>
        <em class="help-block {{ $_error_hidden }}" role="alert">
            @error($name)
            {{ $message}}
            @enderror
        </em>
    </div>
</div>

