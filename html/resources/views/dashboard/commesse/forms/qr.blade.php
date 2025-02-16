@php
    $urlMarkIn = route('commessa.mark-in', $el->id);
    $urlMarkOut = route('commessa.mark-out', $el->id);

    $writer = new Endroid\QrCode\Writer\PngWriter();
    $qrCodeMarkIn = Endroid\QrCode\QrCode::create($urlMarkIn)
        ->setSize(500)
        ->setMargin(10)
        ->setRoundBlockSizeMode(Endroid\QrCode\RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Endroid\QrCode\Color\Color(0, 0, 0))
        ->setBackgroundColor(new Endroid\QrCode\Color\Color(255, 255, 255));
    $base64MarkIn = $writer->write($qrCodeMarkIn)->getString();

    $qrCodeMarkOut = Endroid\QrCode\QrCode::create($urlMarkOut)
        ->setSize(500)
        ->setMargin(10)
        ->setRoundBlockSizeMode(Endroid\QrCode\RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Endroid\QrCode\Color\Color(0, 0, 0))
        ->setBackgroundColor(new Endroid\QrCode\Color\Color(255, 255, 255));
    $base64MarkOut = $writer->write($qrCodeMarkOut)->getString();

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
                    <img class="img-fluid" src="data:image/png;base64, {{ base64_encode($base64MarkIn) }} " />
                    <div>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['png', 'generate' => urlencode($urlMarkIn)]) }}" target="_blank">PNG</a>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex flex-column align-items-center">
                    <span>Timbratura prod. uscita</span>
                    <img class="img-fluid" src="data:image/png;base64, {{ base64_encode($base64MarkOut) }} " />
                    <div>
                        <a class="mx-1 btn btn-link btn-sm" href="{{ route('commessa.qr', ['png', 'generate' => urlencode($urlMarkOut)]) }}" target="_blank">PNG</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
