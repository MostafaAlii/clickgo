<div class="modal fade" id="addNewAdmin" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="adminForm" autocomplete="off">
                <!--begin::Modal header-->
                <div class="modal-header bg-dark">
                    <!--begin::Modal title-->
                    <h2 id="modal-title" class="card-title align-items-start flex-column text-gray-500">
                        <span class="card-label fw-bolder fs-3 mb-1">
                            <span class="menu-icon">
                                <i class="bi bi-people fs-1 text-gray-500"></i>
                            </span>
                        </span>
                        <span class="title-text"></span>
                    </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                        <!--begin::Input group Name & Email-->
                        <div class="row mb-5">
                            <!--Start Name Filed -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-bold mb-2">{{ trans('dashboard/admin.name') }}</label>
                                <input type="text" class="form-control form-control-solid" placeholder="{{ trans('dashboard/admin.type_name_placeholder') }}" name="name" />
                                <span class="form-control-feedback text-danger" id="nameError" data-field="name"></span>
                            </div>
                            <!--End Name Filed-->
                            <!--Start Email Filed-->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-bold mb-2">{{ trans('dashboard/admin.email') }}</label>
                                <input type="text" class="form-control form-control-solid" placeholder="{{ trans('dashboard/admin.type_email_placeholder') }}" name="email" value="" />
                                <span class="form-control-feedback text-danger" id="emailError" data-field="email"></span>
                            </div>
                            <!--End Email Filed-->
                        </div>
                        <!--end::Input group Name & Email-->
                        <!--Start Password Filed-->
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-bold mb-2">{{ trans('dashboard/admin.password') }}</label>
                                <input type="password" class="form-control form-control-solid" placeholder="{{ trans('dashboard/admin.type_password_placeholder') }}" name="password" />
                                <span class="form-control-feedback text-danger" id="passwordError" data-field="password"></span>
                            </div>
                            <!--Start Phone -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-bold mb-2">{{ trans('dashboard/admin.phone') }}</label>
                                <input type="text" class="form-control form-control-solid" placeholder="{{ trans('dashboard/admin.type_phone_placeholder') }}" name="phone" />
                                <span class="form-control-feedback text-danger" id="phoneError" data-field="phone"></span>
                            </div>
                            <!-- End Phone -->
                        </div>
                        <!--End Password Filed-->

                        <!--Start Status & Link Protection Group-->
                        <div class="row mb-5">
                            <!-- Start Status Switch -->
                            <div class="col-md-6 fv-row form-check form-switch form-check-custom form-check-solid">
                                <label class="form-check-label fs-5 fw-bold mb-2" for="status">
                                    {{ trans('dashboard/admin.status') }}
                                </label>
                                <input class="form-check-input" name="status" type="checkbox" value="active" />
                                <span class="form-control-feedback text-danger" id="statusError" data-field="status"></span>
                            </div>
                            <!-- End Status Switch -->
                            <!-- Start Link Protection Switch -->
                            <div class="col-md-6 fv-row form-check form-switch form-check-custom form-check-solid">
                                <label class="form-check-label fs-5 fw-bold mb-2" for="link_protection">
                                    {{ trans('dashboard/admin.link_protection_status') }}
                                </label>
                                <input class="form-check-input link_protection" name="link_password_status" type="checkbox" value="1" />
                                <span class="form-control-feedback text-danger" id="link_protectionError" data-field="link_protection"></span>
                            </div>
                            <!-- End Link Protection Switch -->
                        </div>
                        <!--End Status & Link Protection Group-->


                        <!-- Start Link Protection Password-->
                        <div class="row mb-5">
                            <!--Start Name Filed -->
                            <div class="col-md-6 fv-row"></div>
                            <!--End Name Filed-->
                            <!--Start Password Protcetion Filed-->
                            <div class="col-md-6 fv-row" id="password_protection_field" style="display:none;">
                                <label class="required fs-5 fw-bold mb-2">{{ trans('dashboard/admin.link_protection_password') }}</label>
                                <input type="password" class="form-control form-control-solid link_password_protection" placeholder="{{ trans('dashboard/admin.type_link_protection_password') }}" name="link_password_protection" />
                                <span class="form-control-feedback text-danger" id="link_password_protectionError" data-field="link_password_protection"></span>
                            </div>
                            <!--End Password Protcetion Filed-->
                        </div>
                        <!-- End Link Protection Password-->

                        <!-- Start AdminType Select -->
                        <div class="row mb-5">
                            <select class="col-md-6 fv-row form-select form-select-solid" aria-label="Select example" name="type">
                                <option>Open this select menu</option>
                                <option value="admin">Admin</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                            <span class="form-control-feedback text-danger" id="typeError" data-field="type"></span>
                        </div>
                        <!-- End AdminType Select -->
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center bg-hover-lighten text-hover-inverse-lighten">
                    <!--begin::Button-->
                    <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning" data-bs-dismiss="modal">
                        {{ trans('dashboard/general.cancel') }}
                    </button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="button" id="saveBtn" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success">
                        <span class="indicator-label"></span>
                        <span class="indicator-progress">
                            {{ trans('dashboard/general.please_wait') }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!-- Modal -->
