<ul class="list-group">
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">{{ $el->utente ? $el->utente->label : 'ND' }}</div>
                    <div class="widget-subheading">
                        @if($el->utente)
                            @if($el->utente->telefono)
                                <i class="far fa-phone-alt"></i> <a href="tel:{{ strtolower($el->utente->telefono) }}">{{ strtolower($el->utente->telefono) }}</a>
                            @endif
                            @if($el->utente->email)
                            <i class="far fa-envelope-open"></i> <a href="mailto:{{ strtolower($el->utente->email) }}">{{ strtolower($el->utente->email) }}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">Alert</div>
                    <div class="widget-subheading"></div>
                </div>
                <div class="widget-content-right text-center" style="min-width: 120px;">
                    @component('layouts.components.humanactivity.alert', ['el' => $el])
                    @endcomponent
                    <br>
                    @component('layouts.components.humanactivity.data', ['el' => $el])
                    @endcomponent
                </div>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">Heart monitor</div>
                    <div class="widget-subheading"></div>
                </div>
                <div class="widget-content-right text-center" style="min-width: 120px;">
                    <span class="badge badge-light">{{ $el->hrm_bpm }} bpm</span> <i class="fas fa-heartbeat text-danger animated heartBeat infinite"></i>
                    <br>
                    @component('layouts.components.humanactivity.hrm', ['el' => $el])
                    @endcomponent
                </div>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">Stress level</div>
                    <div class="widget-subheading"></div>
                </div>
                <div class="widget-content-right text-center" style="min-width: 120px;">
                    @component('layouts.components.humanactivity.stress', ['el' => $el])
                    @endcomponent
                </div>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">Man down</div>
                    <div class="widget-subheading">
                        {{ $el->latitude ? $el->latitude.','.$el->longitude : null }}
                    </div>
                </div>
                <div class="widget-content-right text-center" style="min-width: 120px;">
                    @component('layouts.components.humanactivity.mandown', ['el' => $el])
                    @endcomponent
                </div>
            </div>
        </div>
    </li>
    @if($el->latitude)
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper">
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $el->latitude }},{{ $el->longitude }}" class="btn btn-light btn-block btn-sm" target="_blank">
                    Ottieni indicazioni
                </a>
            </div>
        </div>
    </li>
    @endif
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">{{ $el->device->type->brand }}</div>
                    <div class="widget-subheading">
                        {{ $el->device->type->label }}
                    </div>
                </div>
                <div class="widget-content-right text-center" style="min-width: 120px;">
                    @component('layouts.components.humanactivity.device', ['el' => $el])
                    @endcomponent
                </div>
            </div>
        </div>
    </li>
    @if($el->checked_at)
    <li class="list-group-item">
        <div class="widget-content p-0">
            <div class="widget-content-wrapper" style="min-height: 48px">
                <div class="widget-content-left">
                    <div class="widget-heading">
                        Checked | {{ dataOra($el->checked_at) }}
                    </div>
                    <div class="widget-subheading">
                        {{ Str::title($el->checkedBy->name) }}
                    </div>
                </div>
            </div>
        </div>
    </li>
    @endif
</ul>
