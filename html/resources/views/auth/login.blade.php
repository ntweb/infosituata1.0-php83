@extends('layouts.app')

@section('content')

    <form class="" action="{{ route('login') }}" method="post">
        @csrf
        <div class="modal-content">
            <div class="modal-body">
                <div class="h5 modal-title text-center">
                    <h4 class="mt-2">
                        <div>Login</div>
                        <span>Inserisci i tuoi dati per accedere</span>
                    </h4>
                </div>

                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <input name="email" id="exampleEmail" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <input name="password" id="examplePassword" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <div class="float-left">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="btn-lg btn btn-link">Recupera password</a>
                    @endif
                </div>
                <div class="float-right">
                    <button class="btn btn-primary btn-lg" type="submit">Login</button>
                </div>
            </div>
        </div>
    </form>

@endsection
