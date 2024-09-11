@php
    $action = route('commessa.update', [$el->id, '_type' => 'json']);
    $class = 'ns';

    $extraFields = [];
    if ($el->extra_fields) {
        $extraFields = json_decode($el->extra_fields, true);
        // dd($extraFields);
    }

@endphp

<form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post">

    @csrf
    @method('PUT')

    <input type="hidden" name="_module" value="extra-field">

    <div class="modal fade" id="extraFieldModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Extra field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        @for($i=1;$i<=5;$i++)
                        <div class="col mx-1">
                            <div class="row">

                                <div class="form-group form-group-sm col-md-12">
                                    <label class="mb-0">Extra field {{ $i }}</label>
                                    <input class="form-control form-control-sm" type="text" name="extra[{{$i}}][label]" value="{{ @$extraFields[$i]['label']}} ">
                                </div>


                                @for($ii=1;$ii<=5;$ii++)
                                    <div class="form-group form-group-sm col-md-7">
                                        <label class="mb-0">Val {{ $ii }}</label>
                                        <input class="form-control form-control-sm" type="text" name="extra[{{ $i }}][v][{{ $ii }}]" value="{{ @$extraFields[$i]['v'][$ii] }} ">
                                    </div>

                                    <div class="form-group form-group-sm col-md-5">
                                        <label class="mb-0">Col {{ $ii }}</label>
                                        <input class="form-control form-control-sm" type="color" name="extra[{{ $i }}][c][{{ $ii }}]" value="{{ @$extraFields[$i]['c'][$ii] }}">
                                    </div>
                                @endfor
                            </div>
                        </div>
                        @endfor
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
