@extends('dashboard.layouts.master')

@section('css')
<!-- Add any custom CSS here -->
<style>
    .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    }

    .switch input {display:none;}

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 18px; width: 18px;
    left: 3px; bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
    }

    input:checked + .slider {
    background-color: #28a745;
    }

    input:checked + .slider:before {
    transform: translateX(26px);
    }
</style>
@endsection

@section('pageTitle')
{{$pageTitle}}
@endsection

@section('breadcrumbs')
@parent
<x-dashboard.breadcrumb-item route="admin.providers.index" title="{{ $pageTitle }}" />
@endsection

@section('content')
@include('dashboard.layouts.common._partial.messages')
<div id="kt_content_container" class="container-xxl">
    <div class="mb-5 card card-xxl-stretch mb-xl-8">
        <!--begin::Header-->
        <div class="pt-5 border-0 card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="mb-1 card-label fw-bolder fs-3">{{$pageTitle}}</span>
                <span class="mt-1 text-muted fw-bold fs-7">{{$pageTitle}}</span>
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover">
                    <a href="{{ route('admin.providers.create') }}" class="btn btn-sm btn-light btn-active-primary">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                    transform="rotate(-90 11.364 20.364)" fill="black" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        اضافه مزود خدمه جديد
                    </a>
                </div>
            </h3>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="py-3 card-body">
            <!--begin::Table container-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table table-striped table-row-bordered gy-5 gs-7">
                    {!! $dataTable->table() !!}
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{!! $dataTable->scripts() !!}
<script>
    $(document).on('change', '.status-toggle', function() {
        let id = $(this).data('id');

        $.ajax({
            url: `/admin/providers/${id}/toggle-status`,
            method: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                toastr.success(res.message);

                // تحديث الجدول بدون إعادة تحميل الصفحة
                $('.dataTable').DataTable().ajax.reload(null, false);
                // أو:
                // $('.dataTable').DataTable().draw(false);
            },
            error: function() {
                toastr.error('حدث خطأ أثناء تحديث الحالة');
            }
        });
    });
</script>
@endpush
