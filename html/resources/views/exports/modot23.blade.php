<table>
    <thead>
        <tr>
            <th>N</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Tipologia</th>
            <th>Descrizione</th>
            <th>Reparto</th>
            <th>Qualifica</th>
            <th>Tipo lavoratore</th>
            <th>Azione correttiva</th>
            <th>Azioni intraprese</th>
            <th>Preposto</th>
            <th>Stato azione correttiva</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($list as $el)
        <tr>
            <td>{{ $el->n }}</td>
            <td>{{ data($el->data_e_ora) }}</td>
            <td>{{ $el->categoria }}</td>
            <td>{{ $el->tipologia }}</td>
            <td>{{ $el->tipo_incidente }}</td>
            <td>{{ $el->reparto }}</td>
            <td>{{ $el->qualifica }}</td>
            <td>{{ $el->tipo_lavoratore }}</td>
            <td>{{ $el->azioni_da_intr }}</td>
            <td>{{ $el->prop_elim_pericolo }}</td>
            <td>{{ $el->preposto }}</td>
            <td>{{ $el->stato_azioni_da_intr }}</td>
            <td>{{ $el->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
