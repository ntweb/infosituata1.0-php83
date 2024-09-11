<div class="mb-3 card main-card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <div class="card-header-title">
            <i class="header-icon bx bx-user icon-gradient bg-love-kiss"> </i>
            Partecipano a questo topic
        </div>
    </div>
    <div class="card-body">

        <ul class="list-group list-group-flush">
            @foreach($el->utenti as $utente)
            <li class="list-group-item">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left mr-3">
                            <img src="{{ url('assets/images/avatars/1.jpg') }}" alt="" width="36">
                        </div>
                        <div class="widget-content-left">
                            <div class="widget-heading">
                                {{ $utente->label }}
                            </div>
                        </div>
                        <div class="widget-content-right"></div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

    </div>
</div>
