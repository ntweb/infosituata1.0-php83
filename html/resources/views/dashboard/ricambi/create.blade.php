@php
    $action = isset($el) ? route('ricambi.update', [$el->id, '_type' => 'json']) : route('ricambi.store', ['_type' => 'json']);
    $class = 'ns';
@endphp

<div style="max-width: 350px;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="{{ $class }}" action="{{ $action }}" autocomplete="none" method="post" data-callback="closeDrawer();">

                    @csrf
                    @if(isset($el))
                        @method('PUT')
                    @endif

                    <h5>Ricambio</h5>

                    <div class="row">
                        @component('layouts.components.forms.text', ['name' => 'label', 'value' => @$el->label, 'class' => 'col-md-12'])
                            Etichetta
                        @endcomponent
                    </div>

                    <button class="btn btn-primary btn-lg" type="submit">Salva</button>

                </form>
            </div>
        </div>
    </div>

</div>

