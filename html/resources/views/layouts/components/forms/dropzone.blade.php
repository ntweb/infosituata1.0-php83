@php
    $callback = isset($callback) ? $callback : null;
@endphp
<div id="my-dropzone" class="border rounded" data-route="{{ $action }}" style="min-height: 150px" @if($callback) data-callback="{{ $callback }}" @endif>
    <div class="dz-message" data-dz-message>
        <div id="dropzone-container" class="text-center py-4">
            <i class="bx bx-cloud-upload" style="font-size: 50px"></i>
            <p>Cliccare per selezionare i file da caricare <br> o trascinarli in questo riquadro</p>
        </div>
    </div>
</div>
<div class="mb-3 mx-2" id="dropzone-progress-clone" style="display: none">
    <small class="dropzone-filename-placeholder">Nome file</small>
    <div class="progress-bar-animated-alt progress progress-bar-xs">
        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>
</div>
