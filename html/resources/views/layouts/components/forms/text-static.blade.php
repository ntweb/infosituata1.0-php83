<div class="form-group {{ isset($class) ? $class : 'col-md-12' }}">
    <label>{{ isset($label) ? $label : $name }}</label>
    <p class="form-control-static @if(isset($helper)) mb-0 @endif">
        {!! $value !!}
    </p>

    @if(isset($helper))
        <small class="form-text text-muted mt-0">{!! $helper !!}</small>
    @endif

</div>
