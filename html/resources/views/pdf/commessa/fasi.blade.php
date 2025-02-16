    <main>

        <div class="full-width mt-5 text-center">
            <h1>Dettaglio fasi</h1>
        </div>
        <p class="mt-5">
            <span style="width: 4cm; display: inline-block">Numero totale fasi:</span>
            <span style="width: 8cm; display: inline-block">{{ $totaleFasi }}</span>
        </p>
        <p>
            <span style="width: 4cm; display: inline-block">Numero totale sottofasi</span>
            <span style="width: 8cm; display: inline-block">{{ $totaleSottoFasi }}</span>
        </p>

        @foreach($tree as $node)
            <table class="full-width mt-5 bordered" page-break-inside: auto;>
                <thead>
                <tr>
                    <th class="vertical-align-top bgLightGrey text-left" colspan="3">
                        <strong>{{ Str::title($node->label) }}</strong>
                    </th>
                </tr>
                </thead>
                <tr>
                    <td width="40%" class="text-center">
                        <span class="font-size-9">
                            Date preventive
                        </span>
                        <br>
                        <span class="font-size-9">
                            {{ data($node->data_inizio_prevista) }} -  {{ data($node->data_fine_prevista) }}
                        </span>
                    </td>
                    <td width="40%" class="text-center">
                        <span class="font-size-9">
                            Date consuntive
                        </span>
                        <br>
                        <span class="font-size-9">
                            @if ($node->data_inizio_effettiva)
                                {{ data($node->data_inizio_effettiva) }} -  {{ data($node->data_fine_effettiva) }}
                            @else
                                -
                            @endif
                        </span>
                    </td>
                    <td width="20%" class="text-center">
                        <span class="font-size-9">
                            Stato
                        </span>
                        <br>
                        <span class="font-size-9">
                            {{ $node->stato }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="33%" class="text-center">
                        <span class="font-size-9">
                            Costo aziendale previsto.
                        </span>
                        <br>
                        <span class="font-size-9">
                            {{ $node->costo_previsto ? euro($node->costo_previsto) .' €' : '-' }}
                        </span>
                    </td>
                    <td width="33%" class="text-center">
                        <span class="font-size-9">
                            Costo aziendale effettivo.
                        </span>
                        <br>
                        <span class="font-size-9">
                            {{ $node->costo_effettivo ? euro($node->costo_effettivo) .' €' : '-' }}
                        </span>
                    </td>
                    <td width="34%" class="text-center">
                        <span class="font-size-9">
                            Rivendita al cliente.
                        </span>
                        <br>
                        <span class="font-size-9">
                            {{ $node->prezzo_cliente ? euro($node->prezzo_cliente) .' €' : '-' }}
                        </span>
                    </td>
                </tr>
                @foreach($node->children as $child)
                    @if(!$child->item_id)
                        <tr>
                            <td colspan="3">
                                <table class="full-width bordered-left">
                                    <tr>
                                        <td class="vertical-align-top" colspan="3">
                                            <strong>{{ Str::title($child->label) }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%" class="text-center">
                                            <span class="font-size-9">
                                                Date preventive
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                {{ data($child->data_inizio_prevista) }} -  {{ data($child->data_fine_prevista) }}
                                            </span>
                                        </td>
                                        <td width="40%" class="text-center">
                                            <span class="font-size-9">
                                                Date consuntive
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                @if ($child->data_inizio_effettiva)
                                                    {{ data($child->data_inizio_effettiva) }} -  {{ data($child->data_fine_effettiva) }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>
                                        <td width="20%" class="text-center">
                                            <span class="font-size-9">
                                                Stato
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                {{ $child->stato }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="33%" class="text-center">
                                            <span class="font-size-9">
                                                Costo aziendale previsto.
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                {{ $child->costo_previsto ? euro($child->costo_previsto) .' €' : '-' }}
                                            </span>
                                        </td>
                                        <td width="33%" class="text-center">
                                            <span class="font-size-9">
                                                Costo aziendale effettivo.
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                {{ $child->costo_effettivo ? euro($child->costo_effettivo) .' €' : '-' }}
                                            </span>
                                        </td>
                                        <td width="34%" class="text-center">
                                            <span class="font-size-9">
                                                Rivendita al cliente.
                                            </span>
                                            <br>
                                            <span class="font-size-9">
                                                {{ $child->prezzo_cliente ? euro($child->prezzo_cliente) .' €' : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach

    </main>
