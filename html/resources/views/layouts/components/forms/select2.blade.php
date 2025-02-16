@php
    $class = isset($class) ? $class : 'col-md-12';
    $elements = \App\Models\Ricambio::orderBy('label')->get();

    $_read_only = false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;
@endphp

<div class="{{ $class }} @error( $name ) has-error @enderror">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="multiselect-dropdown form-control" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" @if($_read_only) disabled readonly @endif style="width: 100%">
            @foreach($elements as $v => $txt)
            <option value="{{ $v }}" @if($v == old($name, $value)) selected="selected" @endif>Elemento_{{ $txt }}</option>
            @endforeach
        </select>
    </div>
</div>

