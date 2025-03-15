@php
    $tot_costo_previsto = 0;
    $tot_costo_effettivo = 0;
    $tot_prezzo_cliente = 0;
    $tot_ric_pre = 0;
    $tot_ric_cons = 0;
@endphp

<main>

    <div class="full-width mt-5 text-center">
        <h1>Analisi costi e ricavi</h1>
    </div>

    <table class="full-width bordered mt-5">
        <tr>
            <td>
                Costo azi. prev.
                <br>
                <span class="font-size-14">{{ euro($commessa->costo_previsto) }}</span> &euro;
            </td>
            <td>
                Costo azi. cons.
                <br>
                <span class="font-size-14">{{ euro($commessa->costo_effettivo) }}</span> &euro;
            </td>
        </tr>
        <tr>
            <td>
                Riv. cli.
                <br>
                <span class="font-size-14">{{ euro($commessa->prezzo_cliente) }}</span> &euro;
            </td>
            <td>
                Riv. prev.
                <br>
                <span class="font-size-14">{{ euro($commessa->prezzo_cliente - $commessa->costo_previsto) }}</span> &euro;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Ric. cons.
                <br>
                <span class="font-size-14">{{ euro($commessa->prezzo_cliente - $commessa->costo_effettivo) }}</span> &euro;
            </td>
        </tr>
    </table>

    <table class="full-width bordered mt-5" page-break-inside: auto;>
        <thead>
            <tr>
                <th class="bgLightGrey text-left">Fase / sotto fase</th>
                <th class="bgLightGrey text-left">Costo azi. prev</th>
                <th class="bgLightGrey text-left">Costo azi. cons</th>
                <th class="bgLightGrey text-left">Riv. cli</th>
                <th class="bgLightGrey text-left">Ric. pre</th>
                <th class="bgLightGrey text-left">Ric. cons</th>
            </tr>
        </thead>
        @foreach($flatTree as $el)
            @if ($el->type == 'fase_lv_1' || $el->type == 'fase_lv_2')
                @php
                    $color_costo_azi_cons = $el->costo_effettivo < $el->costo_previsto ? 'success' : 'danger';
                    $color_costo_azi_cons = $el->costo_effettivo == $el->costo_previsto ? '' : $color_costo_azi_cons;

                    $ric_pre = $el->prezzo_cliente - $el->costo_previsto;
                    $color_ric_pre = $ric_pre < 0 ? 'danger' : 'success';

                    $ric_cons = $el->prezzo_cliente - $el->costo_effettivo;
                    $color_ric_cons = $ric_cons < 0 ? 'danger' : 'success';

                    $tot_costo_previsto += $el->costo_previsto;
                    $tot_costo_effettivo += $el->costo_effettivo;
                    $tot_prezzo_cliente += $el->prezzo_cliente;
                    $tot_ric_pre += $el->prezzo_cliente - $el->costo_previsto;
                    $tot_ric_cons += $el->prezzo_cliente - $el->$tot_costo_effettivo;
                @endphp
                <tr>
                    <td>{{ $el->label }}</td>
                    <td class="text-right">{{ euro($el->costo_previsto) }} <span class="font-size-8">&euro;</span></td>
                    <td class="text-right"><span class="{{ $color_costo_azi_cons }}">{{ euro($el->costo_effettivo) }}</span> <span class="font-size-8">&euro;</span></td>
                    <td class="text-right">{{ euro($el->prezzo_cliente) }} <span class="font-size-8">&euro;</span></td>
                    <td class="text-right"><span class="{{ $color_ric_pre }}">{{ euro($el->prezzo_cliente - $el->costo_previsto) }}</span> <span class="font-size-8">&euro;</span></td>
                    <td class="text-right"><span class="{{ $color_ric_cons }}">{{ euro($el->prezzo_cliente - $el->costo_effettivo) }}</span> <span class="font-size-8">&euro;</span></td>
                </tr>
            @endif
        @endforeach
        @if(count($flatTree))
            <tr>
                <td></td>
                <td class="text-right"><strong>{{ euro($tot_costo_previsto) }}</strong> <span class="font-size-8">&euro;</span></td>
                <td class="text-right"><strong>{{ euro($tot_costo_effettivo) }}</strong> <span class="font-size-8">&euro;</span></td>
                <td class="text-right"><strong>{{ euro($tot_prezzo_cliente) }}</strong> <span class="font-size-8">&euro;</span></td>
                <td class="text-right"><strong>{{ euro($tot_ric_pre) }}</strong> <span class="font-size-8">&euro;</span></td>
                <td class="text-right"><strong>{{ euro($tot_ric_cons) }}</strong> <span class="font-size-8">&euro;</span></td>
            </tr>
        @endif
    </table>

    <div class="text-center fullwidth mt-5">
        <img src="{{ $chartUrl }}" style="height: 8cm;" />
    </div>

</main>
