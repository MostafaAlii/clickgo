@extends('dashboard.layouts.master')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('pageTitle')
{{$pageTitle}}
@endsection
@section('breadcrumbs')
@parent
<x-dashboard.breadcrumb-item route="admin.categories.index" title="{{ $pageTitle }}" />
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
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="py-3 card-body">
            <div class="card-body">
                @if($mainImage)
                <div class="text-center mb-4">
                    <img src="{{ $mainImage }}" alt="الصورة الرئيسية" class="rounded-circle shadow"
                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;">
                </div>
                @else
                <div class="text-center mb-4">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="صورة افتراضية" class="rounded-circle shadow"
                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;">
                </div>
                @endif
                <p><strong>الاسم:</strong> {{ $provider->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $provider->email }}</p>
                <p><strong>الهاتف:</strong> {{ $provider->phone }}</p>
                <p><strong>المهنة:</strong> {{ $provider->profession->name ?? 'غير محددة' }}</p>
                <p><strong>الوصف:</strong> {{ $provider?->description ?? 'غير محددة' }}</p>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">المستندات المرفوعة</h5>

                    <form action="{{ route('admin.providers.approveAllDocuments', $provider->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success">موافقة على الكل</button>
                    </form>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نوع المستند</th>
                            <th>رابط الملف</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($provider->documentStatuses as $index => $status)
                        <tr>
                            <td>{{ $status->document->name ?? '-' }}</td>
                            <td>
                                @if ($status->attachment)
                                <div>
                                    <img src="{{ asset($status->attachment->file) }}" alt="المرفق"
                                        style="width: 100px; height: auto; border-radius: 5px; margin-bottom: 5px;">
                                </div>
                                <div>
                                    <a href="{{ asset($status->attachment->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">عرض
                                        الملف</a>
                                </div>
                                @else
                                <span class="text-muted">لا يوجد ملف</span>
                                @endif
                            </td>
                            <td>{{ $status->status_label }}</td>
                            <td>
                                @if ($status->isPending())
                                <form action="{{ route('admin.provider-documents.updateStatus', $status->id) }}" method="POST"
                                    class="d-flex gap-1 align-items-center">
                                    @csrf
                                    @method('PUT')

                                    <select name="status" class="form-control status-select" data-index="{{ $index }}" required>
                                        <option value="">-- اختر --</option>
                                        <option value="approved">موافقة</option>
                                        <option value="rejected">رفض</option>
                                    </select>

                                    <input type="text" name="rejection_reason" class="form-control rejection-input"
                                        id="rejection-input-{{ $index }}" placeholder="سبب الرفض" style="display: none;">

                                    <button class="btn btn-sm btn-primary">تحديث</button>
                                </form>
                                @else
                                @if ($status->isRejected())
                                <span class="text-danger">مرفوض</span><br>
                                <small>السبب: {{ $status->rejection_reason }}</small>
                                <form action="{{ route('admin.provider-documents.reupload', $status->id) }}" method="POST" enctype="multipart/form-data"
                                    class="mt-2">
                                    @csrf
                                    @method('POST')
                                    <input type="file" name="new_document" class="form-control-file mb-2" required>
                                    <button type="submit" class="btn btn-sm btn-warning">إعادة رفع المستند</button>
                                </form>
                                @else
                                <span class="text-success">تمت الموافقة</span>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <h5 class="mt-4">معرض الصور</h5>
                <br>
                @if(!empty($galleryImages))
                <div class="row">
                    @foreach($galleryImages as $image)
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-0">
                            <img src="{{ $image }}" class="card-img-top" alt="صورة المعرض" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center p-2">
                                <!-- أيقونة تعديل -->
                                <label class="btn btn-sm btn-primary mx-1 mb-0" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                    <input type="file" class="d-none update-gallery" data-id="{{ $provider->id }}"
                                        data-image="{{ basename($image) }}">
                                </label>

                                <!-- أيقونة حذف -->
                                <button class="btn btn-sm btn-danger mx-1 delete-gallery" data-id="{{ $provider->id }}"
                                    data-image="{{ basename($image) }}" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted">لا توجد صور في المعرض</p>
                @endif
            </div>
        </div>
    </div>
    <!--begin::Body-->
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selects = document.querySelectorAll('.status-select');

        selects.forEach(select => {
            select.addEventListener('change', function () {
                const index = this.dataset.index;
                const rejectionInput = document.getElementById(`rejection-input-${index}`);

                if (this.value === 'rejected') {
                    rejectionInput.style.display = 'block';
                    rejectionInput.required = true;
                } else {
                    rejectionInput.style.display = 'none';
                    rejectionInput.required = false;
                    rejectionInput.value = '';
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

    // حذف صورة
    document.querySelectorAll('.delete-gallery').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('هل أنت متأكد من الحذف؟')) return;

            let id = this.dataset.id;
            let image = this.dataset.image;

            fetch(`/admin/providers/${id}/gallery/${image}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
            .then(res => res.json())
            .then(data => location.reload());
        });
    });

    // تعديل صورة
    document.querySelectorAll('.update-gallery').forEach(input => {
        input.addEventListener('change', function () {
            let id = this.dataset.id;
            let image = this.dataset.image;
            let formData = new FormData();
            formData.append('new_image', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/admin/providers/${id}/gallery/${image}`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => location.reload());
        });
    });

});
</script>
@endpush
