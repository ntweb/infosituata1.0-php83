@php
    $_enable_private = $_enable_private ?? false;
@endphp
@if (count($attachments))

    <div class="table-responsive">
        <table class="mb-0 table table-striped table-bordered table-sm">
            <thead>
            <tr>
                <th>Etichetta</th>
                <th>Filename</th>
                @if($_enable_private)
                    <th></th>
                @endif
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($attachments as $a)
                <tr id="attachment-{{ $a->id }}">
                    <td>{{ Str::title($a->label) }}</td>
                    <td>
                        @if($a->url_cloud)
                            <i class="bx bx-cloud mr-2"></i>
                        @endif
                        <a href="{{ allegato($a, true, $module) }}" target="_blank">
                            {{ $a->url_cloud ?? $a->filename }}
                        </a>
                    </td>
                    @if($_enable_private)
                        <td>
                            @component('layouts.components.forms.toggle', ['name' => 'is_public', 'value' => $a->is_public, 'class' => 'col-md-6', 'toggle' => ['1' => 'Pubblico', '0' => 'Privato'], 'onchange' => 'changeAttachmentVisibility($(this), "'. route('upload.visibility', $a->id) .'")' ])
                            @endcomponent
                        </td>
                    @endif
                    <td class="text-right">
                        @if(isset($_read_only))
                            @if(!$_read_only)
                            <a href="javascript:void(0)" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-danger btn-sm btnDeleteAttachment" data-route="{{ route('upload.delete', [$a->id, 'module' => $module]) }}">cancella</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else
    <div class="col md-12">
        @component('layouts.components.alerts.warning')
            Nessun file allegato
        @endcomponent
    </div>
@endif
