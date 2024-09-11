<div class="card-border mb-3 card card-body border-danger">
    <h5 class="card-title">Cancella elemento</h5>
    Attenzione, cancellando l'elemento non sarà più possibile recuperare il dato.

    <br>
    <br>
    <form action="{{ route('evento.destroy', $el->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <input type="hidden" name="_redirect" value="{{ $redirect }}">

        @component('layouts.components.forms.checkbox', [ 'name' => 'confirm', 'elements' => [0 => 'Conferma cancellazione'], 'value' => 1 ])

        @endcomponent
        <button class="btn btn-block btn-danger">Cancella</button>
    </form>
</div>
