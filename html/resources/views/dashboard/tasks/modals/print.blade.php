@php
    $action = route('task.print', $el->id);
@endphp

<form action="{{ $action }}" autocomplete="none" method="post">
    @csrf

    <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="modalCreateNode" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Stampa task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check">
                                <input name="frontespizio" class="form-check-input" type="checkbox" value="frontespizio" id="frontespizio" disabled checked>
                                <label class="form-check-label" for="frontespizio">
                                    Frontespizio
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="dettaglio_fasi" class="form-check-input" type="checkbox" value="dettaglio_fasi" id="dettaglio_fasi" checked>
                                <label class="form-check-label" for="dettaglio_fasi">
                                    Dettaglio fasi
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="risorse_utilizzate" class="form-check-input" type="checkbox" value="risorse_utilizzate" id="risorse_utilizzate" checked>
                                <label class="form-check-label" for="risorse_utilizzate">
                                    Risorse utilizzate
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="log_risorse" class="form-check-input" type="checkbox" value="log_risorse" id="log_risorse" checked>
                                <label class="form-check-label" for="log_risorse">
                                    Log risorse
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="rapportini" class="form-check-input" type="checkbox" value="rapportini" id="rapportini" checked>
                                <label class="form-check-label" for="rapportini">
                                    Rapportini
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="checklist" class="form-check-input" type="checkbox" value="checklist" id="checklist" checked>
                                <label class="form-check-label" for="checklist">
                                    Checklist
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input name="allegati" class="form-check-input" type="checkbox" value="allegati" id="allegati" checked>
                                <label class="form-check-label" for="allegati">
                                    Elenco allegati
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-new="0">Stampa</button>
                </div>
            </div>
        </div>
    </div>
</form>
