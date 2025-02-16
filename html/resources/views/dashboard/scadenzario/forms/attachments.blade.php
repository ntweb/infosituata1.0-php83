@php
    $action = route('upload.upload');
    $class = 'ajaxForm';

    $_read_only = Gate::allows('can-create') ? false : true;
@endphp


<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-copy-file icon-gradient bg-love-kiss"> </i>
            Allegati
        </div>
    </div>
    <div class="card-body" id="show-attachments">

        @php
            $module = 'scadenza';
            $attachments = $scadenza->attachments()->get();
        @endphp

        @include('dashboard.upload.index')

    </div>
</div>

@if(!$_read_only)
<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-copy-file icon-gradient bg-love-kiss"> </i>
            Carica un nuovo allegato
        </div>
        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm nav btn-group">
                <a data-toggle="tab" href="#tab-upload" class="btn btn-outline-primary show active">Upload</a>
                <a data-toggle="tab" href="#tab-upload-multi" class="btn btn-outline-primary show">Multi</a>
                <a data-toggle="tab" href="#tab-upload-cloud" class="btn btn-outline-primary show">Cloud</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane show active" id="tab-upload">
                {{-- Upload standard --}}
                <form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="getHtml('{{ route.attachments', [$scadenza->id, 'module' => 'scadenza']) }}', '#show-attachments')">
                    @csrf

                    <input type="hidden" name="module" value="scadenza">
                    <input type="hidden" name="scadenza_id" value="{{ $scadenza->id }}">

                    <div class="form-row">
                        @component('layouts.components.forms.file-upload-ajax', ['name' => 'attachment', 'class' => 'col-md-12', 'value' => null])
                            Carica allegato
                        @endcomponent
                        <div class="col-12">
                            <hr>
                            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane show" id="tab-upload-multi">
                {{-- Multi uploads --}}
                <input class="dropzone-append" type="hidden" name="module" value="scadenza">
                <input class="dropzone-append" type="hidden" name="scadenza_id" value="{{ $scadenza->id }}">
                <input class="dropzone-append" type="hidden" name="upload_mode" value="multi">
                @component('layouts.components.forms.dropzone', ['action' => $action, 'callback' => "getHtml('". route.attachments', [$scadenza->id, 'module' => 'scadenza']) ."', '#show-attachments')"])
                @endcomponent
            </div>
            <div class="tab-pane show" id="tab-upload-cloud">
                {{-- Upload cloud --}}
                <form class="ns" id="frmUploadCloud" action="{{ $action }}" autocomplete="none" method="post" data-callback="getHtml('{{ route.attachments', [$scadenza->id, 'module' => 'scadenza']) }}', '#show-attachments'); $('#frmUploadCloud')[0].reset();">
                    @csrf
                    <input type="hidden" name="module" value="scadenza">
                    <input type="hidden" name="scadenza_id" value="{{ $scadenza->id }}">
                    <input type="hidden" name="upload_mode" value="cloud">

                    <div class="form-row">

                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => null, 'class' => 'col-md-12'])
                            Etichetta
                        @endcomponent

                        @component('layouts.components.forms.text', ['name' => 'url_cloud', 'value' => null, 'class' => 'col-md-12'])
                            URL CLoud
                        @endcomponent

                        <div class="col-12">
                            <hr>
                            <button class="btn btn-primary btn-lg" type="submit">Salva</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
