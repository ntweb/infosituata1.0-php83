@php
    $action = route('topic.store-message', $el->id);
    $refreshUrl = route('topic.messages', $el->id);
    $class = 'ns';
@endphp

<form class="{{ $class }}" id="topic" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#message').val(null);reloadTopic('{{ $refreshUrl }}');" data-refresh-url="{{ $refreshUrl }}">

    @csrf

    <input type="hidden" name="_module" id="_module" value="">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon bx bx-chat icon-gradient bg-love-kiss"> </i>
                {{ $el->oggetto }}
            </div>
        </div>
        <div class="card-body">

            <div id="messages"></div>

        </div>
        <div class="d-flex text-right card-footer">
            <div class="col p-0">
                @component('layouts.components.forms.textarea', ['name' => 'message', 'value' => '', 'class' => 'col-md-12 p-0 pr-4'])@endcomponent
            </div>
            <div>
                <button class="btn btn-primary btn-lg" type="submit">Invia</button>
            </div>
        </div>
    </div>
</form>
