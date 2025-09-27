@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')
    <div class="row justify-content-center py-10">
        <div class="col-md-8 col-lg-6">

            <h1 class="display-5">
                {{ __('Reset Password') }}
            </h1>
            <p class="text-muted small">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class=" mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>

                    <div>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror" name="email"
                               value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection
