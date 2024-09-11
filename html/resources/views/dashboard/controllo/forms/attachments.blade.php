@php
    $action = route('upload.upload');
    $class = 'ajaxForm';

    $_read_only = Gate::allows('can-create') ? false : true;
@endphp

<form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="getHtml('{{ route('upload.attachments', [$el->id, 'module' => 'manutenzione']) }}', '#show-attachments')">
    @csrf

    <input type="hidden" name="module" value="manutenzione">
    <input type="hidden" name="manutenzione_id" value="{{ $el->id }}">

    <div class="mb-3 card main-card">
        <div class="card-header-tab card-header-tab-animation card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-copy-file icon-gradient bg-love-kiss"> </i>
                Allegati
            </div>
        </div>
        <div class="card-body">
            <div class="form-row" id="show-attachments">

                @php
                    $module = 'manutenzione';
                    $attachments = $el->attachments()->get();
                @endphp

                @include('dashboard.upload.index')

            </div>

            @if(!$_read_only)
                <hr>

                @component('layouts.components.forms.file-upload-ajax', ['name' => 'attachment', 'class' => 'col-md-12', 'value' => null])
                    Carica allegato
                @endcomponent
            @endif
        </div>
        @if(!$_read_only)
        <div class="d-block text-right card-footer">
            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
        </div>
        @endif
    </div>
</form>
