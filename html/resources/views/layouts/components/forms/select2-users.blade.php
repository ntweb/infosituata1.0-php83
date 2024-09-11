@php
    $class = isset($class) ? $class : 'col-md-12';
    $elements = \App\Models\Utente::orderBy('extras1')->orderBy('extras2')->with('user')->get();

    $_read_only = false;
    if (isset($chek_permission))
        $_read_only = Gate::allows('can-create')  ? false : true;
@endphp

<div class="{{ $class }} @error( $name ) has-error @enderror">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <select class="multiselect-dropdown form-control" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" @if($_read_only) disabled readonly @endif data-dropdownParent="{{ $dropdownParent ?? null }}" style="width: 100%">
            <option value="">-</option>
            @foreach($elements as $utente)
            <option value="{{ $utente->user->id }}" @if($utente->user->id == old($name, $value)) selected="selected" @endif>{{ $utente->user->name }}</option>
            @endforeach
        </select>
    </div>
</div>

