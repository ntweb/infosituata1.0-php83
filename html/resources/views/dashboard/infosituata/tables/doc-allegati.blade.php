@php
    if (count($attachments)) {
        $_power_user =  Auth::user()->power_user ||  Auth::user()->superadmin ||  Auth::user()->azienda_id;
        $_is_subject = Auth::user()->utente_id == $el->id;
        if (!($_power_user || $_is_subject)) {
            // if (true) {
            // dump(Auth::user()->utente_id);
            // dump($el->id);
            // dump($el->attachments);
            $attachments = $attachments->filter(function($attachment) {
                return $attachment->is_public == '1';
            });
            // dd($attachments);
        }
    }
@endphp

@if(count($attachments))
    <div class="table-responsive">
    <table class="mb-0 table table-hover">
        <thead>
        <tr>
            <th class="no-border-top">Etichetta</th>
            <th class="no-border-top">File</th>
        </tr>
        </thead>
        <tbody>
        @foreach($attachments as $attachment)
            @php
                $url = $attachment->url_cloud ?? route('s3.get', $attachment->id);
            @endphp
        <tr>
            <td>{{ Str::title($attachment->label) }}</td>
            <td>
                <a href="{{ $url }}">
                    {{ $attachment->filename }}
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@else
    @component('layouts.components.alerts.info')
        Nessun allegato presente
    @endcomponent
@endif
