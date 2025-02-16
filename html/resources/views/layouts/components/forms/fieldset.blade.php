<div class="card-border card card-body border-focus p-3">
    @if(isset($title))
    <h5 class="card-title">{{ $title }}</h5>
    @endif

    {{ $slot }}
</div>
