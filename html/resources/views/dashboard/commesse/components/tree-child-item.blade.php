<li class="list-group-item no-border-top py-1" style="padding-left: {{ $padding ?? 0 }}">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left d-flex align-items-center">

                @component('dashboard.commesse.components.icons.node-icon', ['node' => $child])
                @endcomponent

                {{-- <i class="bx bxs-circle mr-2" style="color: {{ $child->color ?? '#fefefe' }}"></i>--}}
                <div class="">{{ $child->label }}</div>
            </div>
            <div class="widget-content-right d-flex align-items-center">

                <div class="d-flex justify-content-end" style="min-width: 120px">

                </div>

            </div>
        </div>
    </div>
</li>
