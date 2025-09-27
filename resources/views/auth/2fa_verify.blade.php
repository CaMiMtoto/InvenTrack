@extends('layouts.guest')
@section('title', '2FA Verification')
@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6 order-1 order-md-0">
            <img src="{{ asset('assets/media/shapes/guide.svg') }}" class="tw-object-contain w-100 h-100 rounded" alt="SVG"/>
        </div>
        <div class="col-md-6 order-0 order-md-1">
            <!--begin::Form-->
            <form class="card-body"
                action="{{ route('two-fa.verify-code') }}"
                method="post">
                @csrf
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">
                        Verify Your Email
                    </h1>
                    <!--end::Title-->
                    <p>
                        Please enter the Code sent to your email to verify your account.
                    </p>
                </div>
                <!--begin::Heading-->
                <!--begin::Input group-->
                <div class="mb-10">
                    <!--begin::Label-->
                    <label class="form-label fs-6 fw-bold text-dark">Enter 2FA Code (Email):</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input id="code" type="password"
                           class="form-control  focus:tw-border-zinc-300  form-control-lg @error('code') is-invalid @enderror "
                           name="code" value="{{ old('code') }}" autocomplete="code" autofocus>
                    @error('code')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                </div>

                <!--begin::Actions-->
                <div class="text-center">
                    <!--begin::Submit button-->
                    <button type="submit" id="kt_sign_in_submit"
                            class="btn btn-lg btn-primary w-100 mb-5 tw-bg-gradient-to-r tw-from-primary/5 tw-to-primary">
                    <span class="indicator-label">
                        {{ __('Verify') }}
                    </span>
                    </button>
                    <!--end::Submit button-->
                    <!--begin::Separator-->
                    <p class="text-center">
                        Didn't receive the code or want to resend it?
                        <a href="{{ route('two-fa.resend-code') }}">
                            Resend Code
                        </a>
                    </p>
                    <!--end::Separator-->


                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->

        </div>
    </div>

@endsection

