@php
    $action = route('upload.upload');
    $class = 'ajaxForm';

    $_read_only = Gate::allows('can-create') ? false : true;
    if (isset($el))
        $_read_only = $el->sent_at ? true : $_read_only;

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
            $module = 'messaggio';
            $attachments = $el->attachments()->get();
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
                <form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="getHtml('{{ route('upload.attachments', [$el->id, 'module' => 'messaggio']) }}', '#show-attachments')">
                    @csrf

                    <input type="hidden" name="module" value="messaggio">
                    <input type="hidden" name="messaggio_id" value="{{ $el->id }}">


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
                <input class="dropzone-append" type="hidden" name="module" value="messaggio">
                <input class="dropzone-append" type="hidden" name="messaggio_id" value="{{ $el->id }}">
                <input class="dropzone-append" type="hidden" name="upload_mode" value="multi">
                @component('layouts.components.forms.dropzone', ['action' => $action, 'callback' => "getHtml('". route('upload.attachments', [$el->id, 'module' => 'messaggio']) ."', '#show-attachments')"])
                @endcomponent
            </div>
            <div class="tab-pane show" id="tab-upload-cloud">
                {{-- Upload cloud --}}
                <form class="ns" id="frmUploadCloud" action="{{ $action }}" autocomplete="none" method="post" data-callback="getHtml('{{ route('upload.attachments', [$el->id, 'module' => 'messaggio']) }}', '#show-attachments'); $('#frmUploadCloud')[0].reset();">

                    @csrf
                    <input type="hidden" name="module" value="messaggio">
                    <input type="hidden" name="messaggio_id" value="{{ $el->id }}">
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
