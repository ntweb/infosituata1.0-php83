@extends('layouts.app')

@section('content')

    <form class="" action="{{ route('auth2fa.check') }}" method="post">
        @csrf
        <div class="modal-content">
            <div class="modal-body">
                <div class="h5 modal-title text-center">
                    <h4 class="mt-2">
                        <div>Login</div>
                        <span>Inserisci il codice generato dall'App</span>
                    </h4>
                </div>

                <div class="form-row">

                    @if(session('message'))
                        <div class="col-md-12">
                            @component('layouts.components.alerts.error')
                                {!! session('message') !!}
                            @endcomponent
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <input name="code" id="exampleEmail" placeholder="Codice" type="text"
                                   class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                   autofocus>
                            @error('code')
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
                    <button class="btn btn-primary btn-lg" type="submit">Controlla codice</button>
                </div>
            </div>
        </div>
    </form>

@endsection
