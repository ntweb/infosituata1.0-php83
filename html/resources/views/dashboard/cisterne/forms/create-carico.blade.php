@php
    $action = route('cisterne.carico', [$el->id, '_type' => 'json']);
    $class = 'ns';
@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post" data-callback="window.location.reload(true);">

    @csrf

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-id icon-gradient bg-love-kiss"> </i>
                Inserisci carico cisterna
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">

                @include('dashboard.cisterne.forms.parts.create-carico-fields')

            </div>
        </div>
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
    </div>
</form>
