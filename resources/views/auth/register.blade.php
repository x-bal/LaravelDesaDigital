@extends('layouts.app')

@section('content')
<!-- Loader -->
<div id="loading">
    <img src="../../../assets/img/loader1.svg" class="loader-img" alt="Loader">
</div>
<!-- Main-signin-wrapper -->
<div class="main-signin-wrapper">
    <div class="row text-center pl-0 pr-0 ml-0 mr-0">
        <div class="col-lg-4 d-block mx-auto">
            <div class="card">
                <div class="card-body">
                    <img src="../../../assets/img/brand/logo-1.png" class="mb-3" alt="Logo">
                    <h4>Please Register with Nixlot</h4>
                    <form method="POST" action="{{ route('register') }}" class="text-left mt-3">
                        @csrf
                        <div class="form-group">
                            <label>Firstname &amp; Lastname</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-main-primary btn-block">Create Account</button>
                    </form>
                    <div class="main-signin-footer mg-t-5">
                        <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main-signin-wrapper -->
@endsection