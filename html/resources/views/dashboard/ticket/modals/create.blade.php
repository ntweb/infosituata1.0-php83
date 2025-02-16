@php
    $aree = \App\Http\Controllers\Dashboard\TicketController::aree();
@endphp

<form id="ticketFrm" action="{{ route('ticket.store') }}" class="ns" method="POST" data-callback="onTicketSendCallback();">
    @csrf

    <div class="modal fade" id="modalTicket" tabindex="-1" role="dialog" aria-labelledby="modalTicket" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuovo ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="">
                    <div class="row">

                    <div class="d-none">
                        <input type="text" name="ticketId" id="ticketId" value="">
                        <input type="text" name="ticketUrl" id="ticketUrl" value="">
                    </div>

                    @component('layouts.components.forms.select', ['name' => 'modulo', 'value' => null, 'elements' => $aree, 'class' => 'col-md-12'])
                            Modulo di pertinenza
                        @endcomponent

                        @component('layouts.components.forms.text', ['name' => 'oggetto', 'value' => null, 'class' => 'col-md-12'])
                            Oggetto
                        @endcomponent

                        @component('layouts.components.forms.textarea', ['name' => 'descrizione', 'value' => null, 'class' => 'col-md-12'])
                            Descrizione
                        @endcomponent
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" id="btnSendTicket">
                        Invia
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
