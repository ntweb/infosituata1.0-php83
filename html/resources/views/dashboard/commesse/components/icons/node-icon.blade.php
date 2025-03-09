@if ($node->item_id || $node->type === 'extra')
    @if($node->type == 'utente')
        <i class="bx bx-user {{ $mr ?? 'mr-1' }}"></i>
    @elseif ($node->type == 'mezzo')
        <i class="bx bxs-car-mechanic {{ $mr ?? 'mr-1' }}"></i>
    @elseif ($node->type == 'materiale')
        <i class="bx bx-package {{ $mr ?? 'mr-1' }}"></i>
    @elseif ($node->type == 'attrezzatura')
        <i class="bx bx-package {{ $mr ?? 'mr-1' }}"></i>
    @else
        <i class="bx bxs-extension {{ $mr ?? 'mr-1' }}"></i>
    @endif
@else
    <i class="bx bxs-circle {{ $mr ?? 'mr-1' }}" style="color: {{ $node->color }}"></i>
@endif
