@extends('layouts.guest')
@section('title', 'Login')
@section('content')
    <div class=" p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('login') }}" method="post">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Sign In</h1>
                <!--end::Title-->
                <p>
                    Enter your email and password to login to your account
                </p>
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10 fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bold text-dark">Email</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input id="email" type="email" class="form-control  focus:tw-border-zinc-300  form-control-lg @error('email') is-invalid @enderror " placeholder="example@dom.com"
                       name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10 fv-plugins-icon-container">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bold text-dark fs-6 mb-0">Password</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input class="form-control form-control-lg  focus:tw-border-zinc-300 @error('password') is-invalid border-danger @enderror" type="password" name="password"
                       placeholder="Password" autocomplete="off">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <!--end::Input group-->
            <div class="mb-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-dark" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5 tw-bg-gradient-to-r tw-from-primary/5 tw-to-primary">
                    <span class="indicator-label">
                        {{ __('Login') }}
                    </span>
                </button>
                <!--end::Submit button-->
                <!--begin::Separator-->
                <div class="text-center text-muted text-uppercase fw-bold mb-5">or</div>
                <!--end::Separator-->

                <a class="btn btn-link d-block" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
@endsection
