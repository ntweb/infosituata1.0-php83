@php
    $action = route('upload.upload');
    $class = 'ajaxForm';
@endphp

<div class="modal fade" id="modalUploadDocument" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload documenti</h5>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm nav btn-group">
                        <a data-toggle="tab" href="#tab-upload" class="btn btn-outline-primary show active">Upload</a>
                        <a data-toggle="tab" href="#tab-upload-multi" class="btn btn-outline-primary show">Multi</a>
                        <a data-toggle="tab" href="#tab-upload-cloud" class="btn btn-outline-primary show">Cloud</a>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="node-create-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab-upload">
                        Upload standard
                        <form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal()">
                            @csrf

                            <input type="hidden" name="module" value="commessa">
                            <input type="hidden" name="commesse_id" value="{{ $el->id }}">

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
                        <input class="dropzone-append" type="hidden" name="module" value="commessa">
                        <input class="dropzone-append" type="hidden" name="commesse_id" value="{{ $el->id }}">
                        <input class="dropzone-append" type="hidden" name="upload_mode" value="multi">
                        @component('layouts.components.forms.dropzone', ['action' => $action, 'callback' => "closeAllModal()"])
                        @endcomponent
                    </div>
                    <div class="tab-pane show" id="tab-upload-cloud">
                        Upload cloud
                        <form class="ns" id="frmUploadCloud" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal(); $('#frmUploadCloud')[0].reset();">
                            @csrf
                            <input type="hidden" name="module" value="commessa">
                            <input type="hidden" name="commesse_id" value="{{ $el->id }}">
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
    </div>
</div>





{{--<form class="{{ $class }}" id="frmAjaxForm" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeAllModal()">--}}
{{--    @csrf--}}

{{--    <input type="hidden" name="module" value="commessa">--}}
{{--    <input type="hidden" name="commesse_id" value="{{ $el->id }}">--}}

{{--    <div class="modal fade" id="modalUploadDocument" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel">Upload documento</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body" id="node-create-body">--}}

{{--                    <div class="row">--}}
{{--                        @component('layouts.components.forms.file-upload-ajax', ['name' => 'attachment', 'class' => 'col-md-12', 'value' => null])--}}
{{--                            Carica allegato--}}
{{--                        @endcomponent--}}
{{--                    </div>--}}

{{--                </div>--}}

{{--                <div class="modal-footer">--}}
{{--                    <button type="submit" class="btn btn-primary" data-new="0">Salva</button>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</form>--}}
