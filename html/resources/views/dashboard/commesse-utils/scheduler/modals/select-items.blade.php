@php
    $class = 'ns';
    $action = null;
@endphp

<form class="{{ $class }}" id="frmCreateNode" action="{{ $action }}" autocomplete="none" method="post" data-callback="">
    @csrf

    <div class="modal fade" id="modalAddItemSearch" tabindex="-1" role="dialog" aria-labelledby="modalAddItemSearch" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="node-create-body">

                    <div class="row">
                        <div class="col text-center">
                            @if(isset($sub_title) && !isset($el))
                                <div class="alert alert-info">
                                    {{ $sub_title }}
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <div class="input-group">
                                <input type="hidden" class="searchParameter" name="_module" value="{{ request()->input('_module') }}">

                                @if(isset($gruppi))
                                    <input type="hidden" class="searchParameter" name="gruppo_id">
                                @endif
                                <input type="text" class="form-control searchParameter" name="search" id="textSearchItem" placeholder="Ricerca anagrafica e tag" data-route="{{ $search_route }}">
                                <div class="input-group-append">
                                    @if(isset($gruppi))
                                        <div class="btn-group ">
                                            <span class="input-group-text bg-light" type="button" style="border-radius: unset" id="selectedGruppo">Tutti i gruppi</span>
                                            <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-light" style="border-right: none; border-radius: unset; border-left: 1px solid #dedede;"><span class="sr-only">Toggle Dropdown</span></button>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">

                                                <button type="button" tabindex="0" class="dropdown-item selectGruppo" data-id="" data-label="Tutti i gruppi">
                                                    Tutti i gruppi
                                                </button>
                                                <div class="dropdown-divider"></div>
                                                @foreach($gruppi as $gr)
                                                    <button type="button" tabindex="0" class="dropdown-item selectGruppo" data-id="{{ $gr->id }}" data-label="{{ \Illuminate\Support\Str::title($gr->label) }}">
                                                        {{ \Illuminate\Support\Str::title($gr->label) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <button class="btn btn-light btnSearchItemScheduler" type="button" data-route="{{ $search_route }}"><i class="bx bx-search"></i> Cerca</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2" id="resultSearchItem"></div>

                    </div>


                </div>
            </div>
        </div>
    </div>

</form>
