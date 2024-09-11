<table>
    <thead>
        <tr>
            <th>Item</th>
            <th></th>
            <th>Data presa visione</th>
        </tr>
    </thead>
    <tbody>
    @foreach($list as $el)
        <tr>
            <td>{{ Str::title($el->risorsa->label) }}</td>
            <td>{{ $el->risorsa->controller }}</td>
            <td>{{ dataOra($el->created_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
