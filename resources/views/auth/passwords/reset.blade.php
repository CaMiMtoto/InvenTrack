@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')

    <div class="p-10 p-lg-15 mx-auto">
        <h4>
            {{ __('Reset Password') }}
        </h4>
        <p class="text-muted small">
            {{ __('Change your password here. You will be logged in after changing your password.') }}
        </p>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class=" mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>

                <div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class=" mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>

                <div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class=" mb-3">
                <label for="password-confirm"
                       class=form-label">{{ __('Confirm Password') }}</label>

                <div>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                           required autocomplete="new-password">
                </div>
            </div>

            <div class=" mb-0">
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>

@endsection
