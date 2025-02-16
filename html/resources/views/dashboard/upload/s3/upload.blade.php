@php
    $attachments = \App\Models\AttachmentS3::where('reference_id', $reference_id)
                        ->where('reference_table', $reference_table)
                        ->where('to_delete', '0')
                        ->get();

    $action = route('upload-s3.store');
    $class = 'ajaxForm';

    $_read_only = $read_only ?? false;
    $_enable_private = $enable_private ?? false;
@endphp

<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-copy-file icon-gradient bg-love-kiss"> </i>
            {{ $slot }} | Allegati
        </div>
    </div>
    <div class="card-body" id="show-attachments" data-route="{{ route('upload-s3.index', ['reference_id' => $reference_id, 'reference_table' => $reference_table]) }}">
        @include('dashboard.upload.s3.index')
    </div>
</div>

@if(!$_read_only)
<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-copy-file icon-gradient bg-love-kiss"> </i>
            {{ $slot }} | Nuovo allegato
        </div>
        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm nav btn-group">
                <a data-toggle="tab" href="#tab-upload" class="btn btn-outline-primary active">Upload</a>
                <a data-toggle="tab" href="#tab-upload-multi" class="btn btn-outline-primary show">Multi</a>
                <a data-toggle="tab" href="#tab-upload-cloud" class="btn btn-outline-primary show">Cloud</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab-upload">
                {{-- Upload standard --}}
                <form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="refreshAttachments();">
                    @csrf
                    <input type="hidden" name="reference_id" value="{{ $reference_id }}">
                    <input type="hidden" name="reference_table" value="{{ $reference_table }}">

                    <div class="form-row">
                        @component('layouts.components.forms.file-upload-ajax', ['name' => 'attachment', 'class' => 'col-md-12', 'value' => null])
                            Carica allegato
                        @endcomponent
                        <div class="col-12">
                            <hr>
                            <button class="btn btn-primary btn-lg btnUploadAjax" type="button">Salva</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="tab-pane" id="tab-upload-multi">
                {{-- Multi uploads --}}
                <input class="dropzone-append" type="hidden" name="reference_id" value="{{ $reference_id }}">
                <input class="dropzone-append" type="hidden" name="reference_table" value="{{ $reference_table }}">
                <input class="dropzone-append" type="hidden" name="upload_mode" value="multi">
                @component('layouts.components.forms.dropzone', ['action' => $action, 'callback' => "refreshAttachments();"])
                @endcomponent
            </div>
            <div class="tab-pane" id="tab-upload-cloud">
                {{-- Upload cloud --}}
                <form class="ns" id="frmUploadCloud" action="{{ $action }}" autocomplete="none" method="post" data-callback="refreshAttachments();$('#frmUploadCloud')[0].reset();">
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
    </div>
</div>
@endif
