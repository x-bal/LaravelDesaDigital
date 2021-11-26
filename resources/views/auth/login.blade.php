@extends('layouts.app')

@section('content')
<!-- Loader -->
<div id="loading">
    <img src="../../../assets/img/loader1.svg" class="loader-img" alt="Loader">
</div>
<!-- Loader -->
<!-- Main-signin-wrapper -->
<div class="main-signin-wrapper">
    <div class="row text-center pl-0 pr-0 ml-0 mr-0">
        <div class="col-lg-3 d-block mx-auto">
            <div class="card">
                <div class="card-body">
                    <img src="../../../assets/img/brand/logo-1.png" class="mb-3" alt="Logo">
                    <h4>Please sign in to continue</h4>
                    <form method="POST" action="{{ route('login') }}" class="text-left mt-3">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-main-primary btn-block">Sign In</button>
                    </form>
                    <div class="main-signin-footer mg-t-5">
                        @if (Route::has('password.request'))
                        <p>
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('ForgotPassword?') }}
                            </a>
                        </p>
                        @endif
                        <p>Don't have an account? <a href="{{ route('register') }}">Create an Account</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main-signin-wrapper -->
@endsection