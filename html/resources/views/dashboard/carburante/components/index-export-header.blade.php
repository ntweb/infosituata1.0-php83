<form id="frmFilterSchedePeriod" class="d-flex align-items-center justify-content-end" style="">
    <input type="hidden" id="export" name="export">
    @component('layouts.components.forms.date-picker-range', ['name' => 'dates', 'label' => '',  'start' => isset($dates[0]) ? $dates[0] :  \Carbon\Carbon::now()->toDateString(), 'end' => isset($dates[1]) ? $dates[1] :  \Carbon\Carbon::now()->toDateString()])
    @endcomponent
    <button type="submit" class="btn btn-primary">Filtra</button>
</form>
