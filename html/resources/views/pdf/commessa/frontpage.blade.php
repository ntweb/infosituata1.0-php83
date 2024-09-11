{{-- Footer --}}
<header>
    <table class="full-width mt-5">
        <tr>
            <td class="text-left" style="width: 50%">
                <span class="font-size-11">
                    <b>Commessa: {{ Str::title($commessa->label) }}</b>
                </span>
            </td>
            <td class="text-right">
                <span class="font-size-6 text-uppercase">Stampa commessa</span>
            </td>
        </tr>
    </table>
</header>

{{-- Footer --}}
<footer>
    <table class="full-width">
        <tr>
            <td class="text-left" style="width: 50%">
                <br>
                <span class="font-size-8">
                    {{ Str::title($azienda->label) }}
                </span>
            </td>
            <td class="text-right">
                <em>
                    <span class="font-size-8">
                        pagina creata il {{ date('d/m/Y') }}
                    </span>
                </em>
            </td>
        </tr>
    </table>
</footer>

<main>

    <div class="full-width mt-5 text-center">
        <h1>{{ Str::title($commessa->label) }}</h1>
    </div>
    <p class="mt-5">
        <span style="width: 4cm; display: inline-block">Data creazione</span>
        <span style="width: 8cm; display: inline-block">{{ data($commessa->created_at) }}</span>
    </p>
    <p>
        <span style="width: 4cm; display: inline-block">Data preventive</span>
        <span style="width: 8cm; display: inline-block">{{ data($commessa->data_inizio_prevista) }} -  {{ data($commessa->data_fine_prevista) }}</span>
    </p>
    <p>
        <span style="width: 4cm; display: inline-block">Date consuntive</span>
        <span style="width: 8cm; display: inline-block">
            @if ($commessa->data_inizio_effettiva)
                {{ data($commessa->data_inizio_effettiva) }} -  {{ data($commessa->data_fine_effettiva) }}
            @else
                -
            @endif
        </span>
    </p>

    <table class="full-width mt-5 bordered">
        <tr class="bgLightGrey">
            <td class="vertical-align-top">
                <strong>Fasi</strong>
            </td>
            <td></td>
            <td class="vertical-align-top text-right">
                <strong>Date preventive</strong>
            </td>
            <td class="vertical-align-top text-right">
                <strong>Date consuntive</strong>
            </td>
        </tr>
        @foreach($flatTree as $node)
            @if(!$node->item_id)
            <tr>
                <td>{{ Str::title($node->label) }}</td>
                <td class="text-center">
                    {{ $node->type == 'fase_lv_1' ? 'F' : 'SF' }}
                </td>
                <td class="text-right">
                    {{ data($node->data_inizio_prevista) }} -  {{ data($node->data_fine_prevista) }}
                </td>
                <td class="text-right">
                    @if ($node->data_inizio_effettiva)
                        {{ data($node->data_inizio_effettiva) }} -  {{ data($node->data_fine_effettiva) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
    </table>
</main>
