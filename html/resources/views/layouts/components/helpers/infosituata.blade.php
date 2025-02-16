@php
    /** La visibilità sarà sempre pubbica, se la risorsa è privata ci sarà un redirect **/
    $url = route('infosituata-public.check', [md5($el->id)]);

    $writer = new Endroid\QrCode\Writer\PngWriter();
    $qrCode = Endroid\QrCode\QrCode::create($url)
        ->setSize(500)
        ->setMargin(10)
        ->setRoundBlockSizeMode(Endroid\QrCode\RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Endroid\QrCode\Color\Color(0, 0, 0))
        ->setBackgroundColor(new Endroid\QrCode\Color\Color(255, 255, 255));
    $base64 = $writer->write($qrCode)->getString();

@endphp

<div class="card-hover-shadow-2x mb-3 card">
    <div class="card-header">Infosituata</div>
    <div class="card-body">
        <a href="{{ $url }}">
            <img class="img-fluid" src="data:image/png;base64, {{ base64_encode($base64) }} " >
        </a>
{{--        {{ $url }}--}}
    </div>
    <div class="d-block card-footer">
        <a class="mr-2 btn btn-link btn-sm pull-left" href="{{ $url }}" >Infosituata check</a>
        <a class="mr-2 btn btn-link btn-sm pull-right" href="{{ route('infosituata.qr', ['png', 'generate' => urlencode($url)]) }}" target="_blank">PNG</a>
{{--        <a class="mr-2 btn btn-link btn-sm pull-right" href="{{ route('infosituata.qr', ['svg', 'generate' => urlencode($url)]) }}" target="_blank">SVG</a>--}}
    </div>

    @if($el->controller == 'risorsa')
    <div class="d-block card-footer">

        @php
            $action = route('infosituata.visibility', [$el->id, '_type' => 'json']);
            $class = isset($el) ? 'ns' : null;
        @endphp

        <form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post" data-callback="location.reload();">

            @csrf
            @method('PUT')

            <div class="form-row">

                @component('layouts.components.forms.select', ['name' => 'visibility', 'value' => @$el->visibility, 'elements' => ['private' => 'Privata', 'public' => 'Pubblica'], 'class' => 'col-md-12'])
                    Visibilità
                @endcomponent

                @if($el->controller == 'risorsa')
                    @php
                        $elements = collect([0 => 'Tutti i gruppi']);
                        $gruppi = \App\Models\Gruppo::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id')->toArray();
						//dump($gruppi);
                        $elements = $elements->union($gruppi);
						//dump($elements);

                        $style = $el->visibility == 'private' ? 'display: block;' : 'display: none;';
                    @endphp

                    @component('layouts.components.forms.select', ['name' => 'visibility_gruppi_id', 'value' => @$el->visibility_gruppi_id, 'elements' => $elements, 'class' => 'col-md-12 showHideVisibility', 'style' => $style])
                        Visibilità abilitata per
                    @endcomponent
                @endif

                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <button class="btn btn-primary btn-lg" type="submit">Salva</button>
                    </div>
                </div>

                <div class="col d-none error-box">
                    @component('layouts.components.alerts.error', ['class' => 'col-md-12'])
                        Errore in fase di salvataggio
                    @endcomponent
                </div>

            </div>

        </form>

    </div>
    @endif

</div>

@section('modal')
    @include('dashboard.infosituata.risorse.components.modal-delete-attachment')
@endsection
