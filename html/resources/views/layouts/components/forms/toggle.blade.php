@php
    $class = isset($class) ? $class : 'col-md-12';
    $_onchange = $onchange ?? null;
@endphp

<div class="{{ $class }}">
    <div class="position-relative form-group">
        <label for="exampleEmail11" class="">{{ $slot }}</label>
        <div class="clearfix"></div>
        <input type="checkbox"
               data-toggle="toggle"
               data-on="{{ $toggle['1'] }}"
               data-off="{{ $toggle['0'] }}"
               data-onstyle="success"
               data-offstyle="danger"
               name="{{ $name }}"
               id="{{ isset($id) ? $id : $name }}"
               value="1"
               @if($value == '1') checked @endif
            @if($_onchange) onchange="{{ $_onchange }}" @endif
        >
    </div>
</div>
