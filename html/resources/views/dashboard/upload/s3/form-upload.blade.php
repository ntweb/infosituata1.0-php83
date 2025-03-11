@php
    $action = route('upload-s3.store');
    $class = 'ajaxForm';

    $_read_only = $read_only ?? false;
    $_enable_private = $enable_private ?? false;
@endphp

<h5 class="mb-2">Upload documenti</h5>
<div class="btn-actions-pane-right mb-2">
    <div role="group" class="btn-group-sm nav btn-group">
        <a data-toggle="tab" href="#tab-upload" class="btn btn-outline-primary show active">Upload</a>
        <a data-toggle="tab" href="#tab-upload-multi" class="btn btn-outline-primary show">Multi</a>
        <a data-toggle="tab" href="#tab-upload-cloud" class="btn btn-outline-primary show">Cloud</a>
    </div>
</div>

<hr>

<div class="tab-content">
    <div class="tab-pane show active" id="tab-upload">
        <p class="mb-2">Upload standard</p>
        <form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="{{ $callback }}">
            @csrf

            <input type="hidden" name="reference_id" value="{{ $reference_id }}">
            <input type="hidden" name="reference_table" value="{{ $reference_table }}">

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
         Multi uploads
        <input class="dropzone-append" type="hidden" name="reference_id" value="{{ $reference_id }}">
        <input class="dropzone-append" type="hidden" name="reference_table" value="{{ $reference_table }}">
        <input class="dropzone-append" type="hidden" name="upload_mode" value="multi">
        @component('layouts.components.forms.dropzone', ['action' => $action, 'callback' => "refreshAttachments();".$callback])
        @endcomponent
    </div>
    <div class="tab-pane show" id="tab-upload-cloud">
        <p class="mb-2">Upload cloud</p>
        <form class="ns" id="frmUploadCloud" action="{{ $action }}" autocomplete="none" method="post" data-callback="$('#frmUploadCloud')[0].reset();{{ $callback }}">
            @csrf
            <input type="hidden" name="reference_id" value="{{ $reference_id }}">
            <input type="hidden" name="reference_table" value="{{ $reference_table }}">
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
