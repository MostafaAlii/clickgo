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
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Tabs متعددة اللغات --}}
                <div class="row">
                    <div class="mb-5 hover-scroll-x">
                        <div class="d-grid">
                            <ul class="nav nav-tabs flex-nowrap text-nowrap">
                                @foreach(config('laravellocalization.supportedLocales') as $key=>$lang)
                                <li class="nav-item">
                                    <a class="nav-link @if(app()->getLocale() == $key) active @endif" data-bs-toggle="tab"
                                        href="#{{ $key }}">{{ $lang['native'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        @foreach(config('laravellocalization.supportedLocales') as $key=>$lang)
                        <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="{{$key}}" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="{{$key}}[name]" class="form-label">الاسم / {{ $lang['native'] }}</label>
                                    <input type="text" name="{{$key}}[name]" class="form-control"
                                        value="{{ $service->translate($key)->name ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="{{$key}}[description]" class="form-label">الوصف / {{ $lang['native'] }}</label>
                                    <textarea name="{{$key}}[description]"
                                        class="form-control">{{ $service->translate($key)->description ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="{{$key}}[short_description]" class="form-label">الوصف المختصر / {{ $lang['native']
                                        }}</label>
                                    <textarea name="{{$key}}[short_description]"
                                        class="form-control">{{ $service->translate($key)->short_description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- باقي الحقول --}}
                <div class="row">
                    <div class="col-md-4">
                        <label for="status">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $service->status == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $service->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="category_id">التصنيفات</label>
                        <select name="category_id" class="form-control">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                            <option value="{{ $category['id'] }}" {{ $service->category_id == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="price">السعر</label>
                        <input type="number" name="price" class="form-control" value="{{ $service->price }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4" id="providersDropdownRow">
                        <label for="provider_id">مزودي الخدمات</label>
                        <select name="provider_id" class="form-control">
                            <option value="">اختر المزود</option>
                            @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" {{ $service->provider_id == $provider->id ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- صورة الخدمة --}}
                <div class="row">
                    <div class="col-md-12">
                        <label>الصورة</label>
                        <input type="file" name="service" class="form-control">
                        <img src="{{ $service->getMediaUrl('service', $service, null, 'media', 'service', true) }}" width="100" class="mt-2">
                    </div>
                </div>
                {{-- معرض الصور --}}
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
                                    <input type="file" class="d-none update-gallery" data-id="{{ $service->id }}"
                                        data-image="{{ basename($image) }}">
                                </label>
                                <button type="button" class="btn btn-sm btn-danger mx-1 delete-gallery" data-id="{{ $service->id }}"
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
                <br>
                <button type="submit" class="btn btn-success w-100">تحديث</button>
            </form>
        </div>
        <!--begin::Body-->
    </div>
    @endsection

    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function previewImage(inputId, previewId) {
            let input = document.getElementById(inputId);
            let preview = document.getElementById(previewId);

            input.addEventListener("change", function () {
                let file = input.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = "";
                    preview.style.display = "none";
                }
            });
        }
        function openImageModal(src, title) {
            if (src) {
                let popupImage = document.getElementById("popupImage");
                let modalTitle = document.getElementById("imageModalLabel");
                popupImage.src = src;
                modalTitle.innerText = title;
                let imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
                imageModal.show();
            }
        }
        previewImage("categoryInput", "categoryPreview");
    </script>

    <script>
        Dropzone.autoDiscover = false;
        let galleryDropzone = new Dropzone("#galleryDropzone", {
            url: "#",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFilesize: 5, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictRemoveFile: "حذف",
            dictCancelUpload: "إلغاء",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        galleryDropzone.on("addedfile", function(file) {
            let dataTransfer = new DataTransfer();
            let galleryInput = document.getElementById('galleryInput');
            Array.from(galleryInput.files).forEach(f => dataTransfer.items.add(f));
            dataTransfer.items.add(file);
            galleryInput.files = dataTransfer.files;
        });
        galleryDropzone.on("removedfile", function(file) {
            let dataTransfer = new DataTransfer();
            let galleryInput = document.getElementById('galleryInput');

            Array.from(galleryInput.files).forEach(f => {
                if (f.name !== file.name) {
                    dataTransfer.items.add(f);
                }
            });

            galleryInput.files = dataTransfer.files;
        });
    </script>
    <script>
        document.getElementById('isAdmin').addEventListener('change', function() {
            let providersDropdown = document.getElementById('providersDropdownRow');
            if (this.checked) {
                providersDropdown.style.display = 'none';
            } else {
                providersDropdown.style.display = 'flex';
            }
        });
    </script>
<script>
    document.querySelectorAll('.delete-gallery').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('هل أنت متأكد من الحذف؟')) return;

            let id = this.dataset.id;
            let image = this.dataset.image;

            fetch(`/admin/services/${id}/gallery/${image}`, {
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

            fetch(`/admin/services/${id}/gallery/${image}`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => location.reload());
        });
    });
</script>
    @endpush
