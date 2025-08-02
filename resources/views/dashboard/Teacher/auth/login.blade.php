@extends('dashboard.layouts.login')

@section('css')

@endsection

@section('pageTitle')
    {{ trans('dashboard/auth.teacher_auth_form_title') }}
@endsection

@section('content')
    <!--begin::Content-->
    <div class="p-10 d-flex flex-center flex-column flex-column-fluid pb-lg-20">
        <!--begin::Logo-->
        <a href="#" class="mb-12">
            <img alt="Logo" src="{{ asset('dashboard/assets/media/logos/logo-1.svg') }}" class="h-40px" />
        </a>
        <!--end::Logo-->

        <!--begin::Wrapper-->
        <div class="p-10 mx-auto rounded shadow-sm w-lg-500px bg-body p-lg-15">
            <!--begin::Form-->
            @include('dashboard.layouts.common._partial.messages')
            <form class="form w-100" novalidate="novalidate" method="POST" id="kt_sign_in_form" action="{{route('teacher.post.login')}}">
                @csrf
                <!--begin::Heading-->
                <div class="mb-10 text-center">
                    <!--begin::Title-->
                    <h1 class="mb-3 text-dark">{{ trans('dashboard/auth.teacher_auth_form_title') }}</h1>
                    <!--end::Title-->
                </div>
                <!--begin::Heading-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="form-label fs-6 fw-bolder text-dark">{{ trans('dashboard/auth.email_address') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off" />
                    <!--end::Input-->
                    @error('email')
                        <div class="fv-plugins-message-container">
                            <div data-field="email" data-validator="notEmpty" class="fv-help-block text-danger">{{ $message }}</div>
                        </div>
                    @enderror
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Wrapper-->
                    <div class="mb-2 d-flex flex-stack">
                        <!--begin::Label-->
                        <label class="mb-0 form-label fw-bolder text-dark fs-6">{{ trans('dashboard/auth.password') }}</label>
                        <!--end::Label-->
                        <!--begin::Link-->
                        <a href="{{ route('teacher.forgot.password') }}" class="link-primary fs-6 fw-bolder">{{ trans('dashboard/auth.forgot_password') }}</a>
                        <!--end::Link-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Input-->
                    <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
                    <!--end::Input-->
                    @error('password')
                    <div class="fv-plugins-message-container">
                        <div data-field="password" data-validator="notEmpty" class="fv-help-block text-danger">{{ $message }}</div>
                    </div>
                    @enderror
                </div>
                <!--end::Input group-->
                <!--begin::Actions-->
                <div class="text-center">
                    <!--begin::Submit button-->
                    <button type="submit" id="kt_sign_in_submit" class="mb-5 btn btn-lg btn-primary w-100">
                        <span class="indicator-label">{{ trans('dashboard/auth.login') }}</span>
                        <span class="indicator-progress">
                            {{ trans('dashboard/auth.please_wait') }}
                            <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                        </span>
                    </button>
                    <!--end::Submit button-->
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
@endsection

@section('js')
<script>
    $(document).ready(function() {
        @if(session('toastr'))
            var toastrOptions = session('toastr');
            toastr[toastrOptions.type](toastrOptions.message);
        @endif
    });
</script>
@endsection
