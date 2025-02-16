<div class="dropdown d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Allegati</button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
        <a href="{{ url('download/ANALISI-E-RESOCONTO-ANNUALE-SULLA-BASE-DEI-DATI-RACCOLTI.docx') }}" tabindex="0" class="dropdown-item">Analisi e resoconto annuale</a>
        <a href="{{ url('download/Procedura_raccolta analisi_quasi infortuni.docx') }}" tabindex="0" class="dropdown-item">Procedura raccolta analisi</a>
    </div>
</div>

{{--@can('can_create_mancati_infortuni')--}}
<div class="dropdown d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-sm">Menù</button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu">
        <a href="{{ route('mod-ot23_2024.create') }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-plus mr-2"></i> crea nuovo modulo</a>
        {{-- <a href="{{ route('mod-ot23.create') }}" tabindex="0" class="dropdown-item"><i class="fas fa-fw fa-plus mr-2"></i> crea nuovo modulo</a> --}}
        @can('can_create_mancati_infortuni_export')
            @if($years)
                <h6 tabindex="-1" class="dropdown-header">Export</h6>
                @foreach($years as $year)
                <a href="{{ route('mod-ot23.analysis', ['anno' => $year->anno]) }}" tabindex="0" class="dropdown-item">Mancati infortuni {{ $year->anno }}</a>
                @endforeach
            @endif
        @endcan
    </div>
</div>
{{--@endcan--}}
