@php
    $_icon = isset($icon) ? $icon : 'pe-7s-graph';
    $_subtitle = isset($subtitle) ? $subtitle : null;
    $_right_component = isset($right_component) ? $right_component : null;
@endphp
<div class="app-inner-layout">
    <div class="app-inner-layout__header bg-heavy-rain">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        @if(isset($back))
                            <a href="{{ $back }}" style="text-decoration: none;">
                                <i class="lnr-chevron-left"></i>
                            </a>
                        @else
                            <i class="{{ $_icon }} icon-gradient bg-love-kiss"></i>
                        @endif
                    </div>
                    <div>
                        {{ $slot }}
                        @if($_subtitle)
                            <div class="page-title-subheading">{{ $_subtitle }}</div>
                        @endif
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#modalTicket">
                            <i class="bx bx-help-circle"></i>
                            Apri ticket
                        </button>
                        <div id="header-right-component" class="ml-2">
                            @if($_right_component)
                                @include($_right_component)
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
