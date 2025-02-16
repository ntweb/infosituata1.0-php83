@if($el->type == 'input')
    <i class="bx bx-edit-alt mr-2"></i>
@elseif($el->type == 'textarea')
    <i class="bx bx-edit mr-2"></i>
@elseif($el->type == 'date')
    <i class="bx bx-calendar-edit mr-2"></i>
@elseif($el->type == 'select')
    <i class="bx bx-objects-horizontal-left mr-2"></i>
@elseif($el->type == 'radio')
    <i class="bx bx-radio-circle-marked mr-2"></i>
@else
    <i class="bx bx-checkbox-checked mr-2"></i>
@endif
