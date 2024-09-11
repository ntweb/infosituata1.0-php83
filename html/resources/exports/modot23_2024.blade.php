<table>
    <thead>
        <tr>
            <th>Codice</th>
            <th>Data</th>
            <th>Reparto</th>
            <th>Attività</th>
            <th>Descr. incidente</th>
            <th>Az. migl. tecnico</th>
            <th>Az. migl. formazione</th>
            <th>Az. migl. definizione</th>
            <th>Az. migl. verifica</th>
            <th>Az. migl. altro</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($list as $el)
        <tr>
            <td>{{ $el->codice_evento }}</td>
            <td>{{ data($el->data_e_ora) }}</td>
            <td>{{ $el->reparto }}</td>
            <td>{{ $el->attivita }}</td>
            <td>{{ $el->descrizione_incidente }}</td>
            <td>{{ $el->azioni_migl_prev_tecnico }}</td>
            <td>{{ $el->azioni_migl_prev_formazione }}</td>
            <td>{{ $el->azioni_migl_prev_definizione }}</td>
            <td>{{ $el->azioni_migl_prev_verifica }}</td>
            <td>{{ $el->azioni_migl_prev_altro }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
