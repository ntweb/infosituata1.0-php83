@extends('layouts.app')

@section('content')

    <form class="" action="{{ route('password.email') }}" method="post">
        @csrf
        <div class="modal-content">
            <div class="modal-body">
                <div class="h5 modal-title text-center">
                    <h4 class="mt-2">
                        <div>{{ __('Reset Password') }}</div>
                        <span>Procedura di recupero password</span>
                    </h4>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <input name="email" id="exampleEmail" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer clearfix">
                <div class="float-right">
                    <button class="btn btn-primary btn-lg" type="submit">Invia il link di recupero</button>
                </div>
            </div>
        </div>
    </form>

@endsection
