@php
    $showVisibilita = false;
    if ($attachments->count()) {
        $f = $attachments->first();
        if ($f->reference_table == 'items')
            $showVisibilita = true;
    }

@endphp

@if($attachments->count())
    <div class="table-responsive">
        <table class="mb-0 table table-sm">
            <thead>
            <tr>
                <th class="border-top-0">Etichetta</th>
                @if($showVisibilita)
                <th class="border-top-0">Visibilità</th>
                <th class="border-top-0">Mostra solo in HTML</th>
                @endif
                <th class="border-top-0"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($attachments as $a)
                @php
                    $url = $a->url_cloud ?? route('s3.get', $a->id);
                @endphp
                <tr id="attachment-{{ $a->id }}">
                    <td>
                        <div class="d-block flex-column">
                            <div class="d-block align-items-center">
                                @if($a->url_cloud)
                                    <i class="bx bx-cloud mr-1"></i>
                                @endif
                                <span>{{ Str::title($a->label) }}</span>
                            </div>
                            <small><em><a href="{{ $url }}" target="_blank">{{ $a->url_cloud ?? $a->filename }}</a></em></small>
                        </div>
                    </td>
                    @if($showVisibilita)
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input changeAttachmentVisibility" data-route="{{ route('upload-s3.visibility', [$a->id, 1]) }}" type="radio" name="is_public_{{ $a->id }}" id="is_public1_{{ $a->id }}" value="1" @if($a->is_public == '1') checked @endif>
                            <label class="form-check-label" for="is_public1_{{ $a->id }}">Pubblico</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input changeAttachmentVisibility" data-route="{{ route('upload-s3.visibility', [$a->id, 0]) }}" type="radio" name="is_public_{{ $a->id }}" id="is_public2_{{ $a->id }}" value="0" @if($a->is_public == '0') checked @endif>
                            <label class="form-check-label" for="is_public2_{{ $a->id }}">Privato</label>
                        </div>

                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input changeAttachmentEmbedding" data-route="{{ route('upload-s3.embedded', [$a->id, 1]) }}" type="radio" name="is_embedded_{{ $a->id }}" id="is_embedded1_{{ $a->id }}" value="1" @if($a->is_embedded == '1') checked @endif>
                            <label class="form-check-label" for="is_embedded1_{{ $a->id }}">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input changeAttachmentEmbedding" data-route="{{ route('upload-s3.embedded', [$a->id, 0]) }}" type="radio" name="is_embedded_{{ $a->id }}" id="is_embedded2_{{ $a->id }}" value="0" @if($a->is_embedded == '0') checked @endif>
                            <label class="form-check-label" for="is_public2_{{ $a->id }}">No</label>
                        </div>

                    </td>
                    @endif
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-light copy-url"
                                data-url="{{ $url }}">
                            <i class="bx bx-copy"></i>
                        </button>
                        @can('can_delete_s3_attachment', $a)
                            <button type="button" class="btn btn-danger btn-sm btnDelete"
                                    data-message="Si conferma la cancellazione?"
                                    data-route="{{ route('upload-s3.destroy', [$a->id]) }}"
                                    data-callback="refreshAttachments();"><i class="bx bx-trash"></i></button>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else
    <p>Nessun allegato caricato</p>
@endif
