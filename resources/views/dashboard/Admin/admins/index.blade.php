@extends('dashboard.layouts.master')

@section('css')

@endsection

@section('pageTitle')
    {{$PageTitle}}
@endsection


@section('breadcrumbs')
    @parent
    <x-dashboard.breadcrumb-item route="admin.admins.index" title="{{ $PageTitle }}" />
@endsection


@section('content')
    @include('dashboard.layouts.common._partial.messages')
    <div id="kt_content_container" class="container-xxl">
        <div class="mb-5 card card-xxl-stretch mb-xl-8">
            <!--begin::Header-->
            <div class="pt-5 border-0 card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="mb-1 card-label fw-bolder fs-3">{{$PageTitle}}</span>
                    <span class="mt-1 text-muted fw-bold fs-7">{{$PageTitle}} ( {{Admin::count();}} )</span>
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" >
                        <button type="button" class="btn btn-sm btn-light btn-active-primary" data-toggle="modal" data-target="#addNewAdmin">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)"
                                        fill="black" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                </svg>
                            </span>
                            {{ trans('dashboard/admin.create_admin') }}
                        </button>
                    </div>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="py-3 card-body">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3 table-flush">
                        {!! $dataTable->table() !!}

                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--begin::Body-->
        </div>
        @include('dashboard.Admin.admins.btn.create')
        @include('dashboard.Admin.admins.btn.edit')
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{!! $dataTable->scripts() !!}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // CSRF protection
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Show admin Modal on click
        const button = document.querySelector('button[data-target="#addNewAdmin"]');
        button.addEventListener('click', () => {
            $('#addNewAdmin').modal('show');
        });
        // add fw-bold, fs-6, text-muted classes to table thead
        const thead = document.querySelector('#admins_datatable thead tr');
        thead.classList.add('fw-bold', 'fs-6', 'text-muted');
        // Show Password Protection Field on click the link protection status switch
        $('.link_protection').on('change', function(){
            $(this).is(':checked') ? $('#password_protection_field').fadeIn() : $('.link_password_protection').slideUp();
        });
        // AdminForm Submit
        $('#modal-title .title-text').html("{{trans('dashboard/admin.create_admin_details')}}");
        $('#saveBtn .indicator-label').html("{{ trans('dashboard/general.save') }}");
        var form  = $('#adminForm')[0];
        $('#saveBtn').click(function () {
            var formData = new FormData(form);
            $.ajax({
                url: "{{ route('admin.admins.store') }}",
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function() {
                    $('.indicator-progress').show();
                    $('.indicator-label').hide();
                },
                success: function(response) {
                    $('.indicator-progress').hide();
                    $('.indicator-label').show();
                    form.reset();
                    $('#addNewAdmin').modal('hide');
                    toastr.success("{{ trans('dashboard/general.created_successfully') }}");
                    $('#admins_datatable').draw();
                },
                error: function(error) {
                    if (error.status === 422) {
                        var name = error.responseJSON.errors.name;
                        var email = error.responseJSON.errors.email;
                        var password = error.responseJSON.errors.password;
                        var phone = error.responseJSON.errors.phone;
                        var status = error.responseJSON.errors.status;
                        var link_protection = error.responseJSON.errors.link_password_status;
                        var link_password_protection = error.responseJSON.errors.link_password_protection;
                        var type = error.responseJSON.errors.type;
                        $('#nameError').html("{{ trans('dashboard/admin.name_required') }}");
                        $('#emailError').html("{{ trans('dashboard/admin.email_required') }}");
                        $('#passwordError').html("{{ trans('dashboard/admin.password_required') }}");
                        $('#phoneError').html("{{ trans('dashboard/admin.phone_required') }}");
                        $('#statusError').html("{{ trans('dashboard/admin.status_required') }}");
                        $('#typeError').html("{{ trans('dashboard/admin.type_required') }}");
                    }
                },
            });
        });

        // editBtn ::
        $('body').on('click', '.editBtn', function() {
            var target = $(this).data('id');
            var id = target.replace('#editBtn', '');
            var url = "{{ route('admin.admins.edit', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    $('#editAdmin').modal('show');
                    $('#edit-modal-title .title-text').html("{{ trans('dashboard/admin.edit_admin_details')}}");
                    $('#editSaveBtn').html("{{ trans('dashboard/general.update') }}");
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#phone').val(response.phone);
                    $('select[name="type"]').val(response.type).change();
                    $('#edit_password_protection_field .link_password_protection').val(response.link_password_protection);
                    $('input[name="status"]').prop('checked', response.status === "active");
                    /*if (response.link_password_status === true) {
                        $('input[name="link_password_status"]').prop('checked', true);
                        $('#edit_password_protection_field').fadeIn();
                    } else {
                        $('input[name="link_password_status"]').prop('checked', false);
                        $('#edit_password_protection_field').hide();
                    }*/
                    $('input[name="link_password_status"]').prop('checked', response.link_password_status === true);
                    $('#edit_password_protection_field').toggle(response.link_password_status === true);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
@endpush
