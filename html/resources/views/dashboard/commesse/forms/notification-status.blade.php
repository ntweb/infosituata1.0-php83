@php
    $action = route('commessa.notifications', ['_type' => 'json']);
    $class = 'ns';



@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf

    <input type="hidden" name="_module" value="notification-status">
    <input type="hidden" name="commesse_root_id" value="{{ $el->id }}">

    <div class="modal fade" id="notificationStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notifiche cambio stato fasi / sottofasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        @component('layouts.components.forms.select2-multiple', ['name' => 'users_ids', 'id' => 'users_ids',  'value' => '', 'class' => 'col-md-12', 'elements' => $users, 'elementsSelected' => $usersNotificationSel])
                            Utenti
                        @endcomponent
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </div>
        </div>
    </div>

</form>
