@php
$device = $humanactivity->device;
$utente = $humanactivity->utente;
@endphp
@component('mail::message')
# Human Activity Monitor : Alert

<p>
Tipo di alert: <em>{{ $humanactivity->alert == 'auto' ? 'Automatico' : 'Manuale' }}</em>
<br>
@if($humanactivity->alert == 'auto')
Allarme lanciato <em>automaticamente</em> dal dispositivo <em>{{ strtoupper($device->label) }}</em> ({{ $device->identifier }}). Prestare soccorso a: <b>{{ strtoupper($utente->label) }}</b>
@else
Allarme lanciato <em>manualmente</em> da {{ $utente ? strtoupper($utente->label) : 'ND' }} per mezzo del dispositivo <em>{{ strtoupper($device->label) }}</em> ({{ $device->identifier }})
@endif
</p>

@component('mail::table')
| Man down | HRM | GPS |
|:------------- |:------------- |:-------- |
| {{ $humanactivity->man_down =='up' ? 'no' : 'si' }} | {{ $humanactivity->hrm_bpm }} bpm | {{ $humanactivity->latitude ? $humanactivity->latitude.','.$humanactivity->longitude : 'nd,nd' }} |
@endcomponent

@component('mail::button', ['url' => url('dashboard/humanactivity'), 'color' => 'red'])
Human Activity Monitor
@endcomponent

@endcomponent
