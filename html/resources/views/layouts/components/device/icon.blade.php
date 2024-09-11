@if($type->hw == 'smartwatch')
    <i class="pe-7s-wristwatch"> </i>
@elseif($type->hw == 'smartphone')
    <i class="lnr-smartphone"> </i>
@elseif($type->hw == 'laptop')
    <i class="lnr-laptop"> </i>
@elseif($type->hw == 'pc')
    <i class="lnr-laptop-phone"> </i>
@else
    <i class="lnr-tablet"> </i>
@endif
