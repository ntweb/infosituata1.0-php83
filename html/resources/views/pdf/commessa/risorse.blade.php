    <main>

        <div class="full-width mt-5 text-center">
            <h1>Risorse utilizzate</h1>
        </div>

        @foreach($nodeWithRisorse as $node)
            <table class="full-width mt-5 bordered" page-break-inside: auto;>
                <thead>
                <tr>
                    <th class="vertical-align-top bgLightGrey text-left">
                        <strong>{{ Str::title($node->label) }}</strong>
                    </th>
                </tr>
                </thead>
                @foreach($node->descendants as $child)
                    <tr>
                        <td>
                            <table class="full-width">
                                <tr>
                                    <td class="vertical-align-top">
                                        <strong>{{ Str::title($child->label) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        {{ $child->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="font-size-9">
                                            Date di lavorazione previste
                                        </span>
                                        <span class="font-size-9">
                                            {{ data($child->data_inizio_prevista) }} -  {{ data($child->data_fine_prevista) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <span class="font-size-9">
                                            Costo giornaliero.
                                        </span>
                                        <span class="font-size-9">
                                            {{ $child->costo_item_giornaliero_previsto ? euro($child->costo_item_giornaliero_previsto) .' €' : '-' }}
                                        </span>
                                    </td>
                                    <td width="50%">
                                        <span class="font-size-9">
                                            Costo orario.
                                        </span>
                                        <span class="font-size-9">
                                            {{ $child->costo_item_orario_previsto ? euro($child->costo_item_orario_previsto) .' €' : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach

    </main>
