
        @if(count($schedeCarburante))
        <div class="table-responsive">
            <table class="mb-0 table table-hover">
                <thead>
                <tr>
                    <th>Mezzo / Attr.</th>
                    <th>Carburante</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                @foreach($schedeCarburante as $el)
                    <tr>
                        <td>
                            {{ Str::title($el->item->label) }}
                        </td>
                        <td>{{ $el->litri }}</td>
                        <td>{{ data($el->data) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="alert alert-info">
                Nessun log schede carburante presente
            </p>
        @endif
