@if(count($listAttachments))
    <div class="p-2">
        <div class="table-responsive">
            <table class="mb-0 table table-sm" id="avvisi_dt">
                <thead class="bg-heavy-rain">
                <tr>
                    <th>Etichetta</th>
                    <th>File</th>
                    <th>Allegato a</th>

                    <th class="text-right">Caricato il</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($listAttachments as $el)
                    @php
                        $url = $el->url_cloud ?? route('S3.get', $el->id);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ $url }}" target="_blank">
                                {{ Str::title($el->label) }}
                            </a>
                        </td>
                        <td>
                            @if($el->url_cloud)
                                <i class="bx bx-cloud mr-2"></i>
                            @endif
                            <a href="{{ $url }}" target="_blank">
                                {{ $el->url_cloud ?? $el->filename }}
                            </a>
                        </td>
                        <td>{{ Str::title($el->task->label) }}</td>
                        <td>
                            <div class="d-flex flex-column align-items-end">
                                <small>{{ $el->created_at }}</small>
                            </div>
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn btn-outline-danger btn-sm btnDelete"
                                    data-message="Si conferma la cancellazione?"
                                    data-route="{{ route('upload-s3.destroy', [$el->id]) }}"
                                    data-callback="$('#refreshAllegati').trigger('click');"><i class="bx bx-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="p-4">
        @component('layouts.components.alerts.info')
            Nessun allegato trovato
        @endcomponent
    </div>
@endif

