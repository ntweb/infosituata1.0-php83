@extends('layouts.app')

@section('content')

    <script>
        function logOut403() {
            const elem = document.getElementById("frmLogout");
            elem.submit();
        }
    </script>

    <div class="modal-content">
        <div class="modal-body">
            <div class="h5 modal-title text-center">
                <h4 class="mt-2">
                    403 error
                </h4>
                <p>Account non autorizzato a visualizzare <br> la seguente risorsa</p>
            </div>
            <form action="{{ route('logout') }}" id="frmLogout" method="post">@csrf</form>
            <div class="h5 modal-title text-center">
                <button class="btn-shadow btn btn-secondary" onclick="history.back()">Torna indietro</button>
                <button class="btn-shadow btn-shine btn btn-focus" onclick="logOut403()">Esegui il logout</button>
            </div>
        </div>
    </div>

@endsection
