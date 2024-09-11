@if (Gate::allows('is-commesse-module-enabled'))
    @php
        $class = isset($class) ? $class : 'col-md-12';
        $id_utente = isset($id_utente) ? $id_utente : getUtenteIdBySessionUser();
        $data = isset($data) ? $data : \Carbon\Carbon::now()->toDateString();

        $elements = collect([]);

        if ($id_utente) {
            $elements = \App\Models\Commessa::where('item_id', $id_utente)
                    ->where('data_inizio_prevista', '<=', $data)
                    ->where('data_fine_prevista', '>=', $data)
                    ->with('root', 'parent')
                    ->get();

            $elements = $elements->sortBy(function($item, $key) {
               return $item->root->label.' '.$item->parent->label;
            });

        }

        $_read_only = false;
        if (isset($chek_permission))
            $_read_only = Gate::allows('can-create')  ? false : true;
    @endphp


    @if($elements->count())
    <div class="{{ $class }} @error( $name ) has-error @enderror">
        <div class="position-relative form-group">
            <label for="exampleEmail11" class="">{{ $slot }}</label>
            <select class="multiselect-dropdown-xxx form-control" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" @if($_read_only) disabled readonly @endif>
                <option value="">-</option>
                @foreach($elements as $txt)
                    <option value="{{ $txt->id }}" @if($txt->id == old($name, $value)) selected="selected" @endif>{{ Str::title($txt->root->label) }} / {{ Str::title($txt->parent->label) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

@endif

