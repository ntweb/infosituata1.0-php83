@php
    $urlMarkIn = route('commessa.mark-in', $el->id);
    $urlMarkOut = route('commessa.mark-out', $el->id);
@endphp
<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            QR per timbrature di produzione
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="d-flex flex-column align-items-center">
                    <span>Timbratura prod. ingresso</span>
                    <img class="img-fluid" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(500)->generate($urlMarkIn)) }} " />
                    <div>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['png', 'generate' => urlencode($urlMarkIn)]) }}" target="_blank">PNG</a>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['svg', 'generate' => urlencode($urlMarkIn)]) }}" target="_blank">SVG</a>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex flex-column align-items-center">
                    <span>Timbratura prod. uscita</span>
                    <img class="img-fluid" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(500)->generate($urlMarkOut)) }} " />
                    <div>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['png', 'generate' => urlencode($urlMarkOut)]) }}" target="_blank">PNG</a>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['svg', 'generate' => urlencode($urlMarkOut)]) }}" target="_blank">SVG</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
