@extends('dashboard.layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

<style>
#galleryDropzone {
background-color: #f9f9f9;
border: 2px dashed #ddd;
border-radius: 0.75rem;
padding: 20px;
transition: border-color 0.3s, background-color 0.3s;
}

#galleryDropzone.dz-drag-hover {
background-color: #eef7ff;
border-color: #009ef7;
}

#galleryDropzone .dz-message {
color: #5e6278;
}

.dropzone .dz-preview .dz-image {
border-radius: 0.75rem;
}

.dropzone .dz-remove {
font-size: 0.9rem;
color: #f1416c;
cursor: pointer;
}
</style>
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
            <form action="{{ route('admin.providers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-5 hover-scroll-x">
                        <div class="d-grid">
                            <ul class="nav nav-tabs flex-nowrap text-nowrap">
                                @foreach(config('laravellocalization.supportedLocales') as $key=>$lang)
                                <li class="nav-item">
                                    <a class="nav-link
                                                                    @if(app()->getLocale() == $key)
                                                                        btn btn-active-light btn-color-gray-600 btn-active-color-success rounded-bottom-0 active
                                                                    @endif
                                                                " data-bs-toggle="tab" href="#{{ $key }}">{{ $lang['native'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        @foreach(config('laravellocalization.supportedLocales') as $key=>$lang)
                        <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="{{$key}}" role="tabpanel"
                            aria-labelledby="{{$key}}-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="{{$key}}[description]" class="form-label">{{trans('dashboard/provider.description') . '
                                        / ' .
                                        $lang['native']}}</label>
                                    <textarea name="{{$key}}[description]" id="{{$key}}[description]" cols="30" rows="10"
                                        class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>المهنة</label>
                            <select name="profession_id" id="profession-select" class="form-control" required>
                                <option value="">-- اختر المهنة --</option>
                                @foreach($professions as $profession)
                                <option value="{{ $profession->id }}">
                                    {{ $profession->name ?? __('بدون اسم') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div id="document-upload-area" class="mt-4"></div>
        </div>
        <!-- End Location -->
        @include('dashboard.Admin.providers.btn.document_preview')
        <br>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="p-3 mb-3 text-center border rounded">
                    <label for="image" class="form-label fw-bold">الصوره</label>
                    <input class="form-control" type="file" name="provider" id="providerInput" accept="image/*">
                    <div class="mt-2">
                        <img id="providerPreview" src="" alt="" width="100" style="cursor: pointer;"
                            onclick="openImageModal(this.src, 'الصوره')">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="form-label fw-bold">معرض الصور (Gallery)</label>
                <div id="galleryDropzone" class="dropzone dz-clickable">
                    <div class="dz-message needsclick">
                        <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                        <div class="fs-6 fw-bold mt-2">اسحب الصور هنا أو اضغط للرفع</div>
                        <small class="text-muted">يمكنك رفع أكثر من صورة</small>
                    </div>
                </div>
                <!-- مدخل مخفي لإرسال الصور مع الفورم -->
                <input type="file" name="gallery[]" id="galleryInput" multiple hidden>
            </div>
        </div>
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">عرض الصورة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="text-center modal-body">
                        <img id="popupImage" src="" class="rounded img-fluid" style="max-width: 100%; max-height: 80vh;">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100">حفظ</button>
        </form>
    </div>
    <!--begin::Body-->
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    const professions = @json($professions);
    document.getElementById('profession-select').addEventListener('change', function () {
    const professionId = this.value;
    const selected = professions.find(p => p.id == professionId);
    let html = '';
    if (selected && selected.documents.length) {
        html += '<div class="row">';
        selected.documents.forEach((doc, index) => {
            const name = doc.name ?? (doc.translations?.[0]?.name ?? 'مستند');
            const inputId = `doc-input-${doc.id}`;
            const previewId = `preview-${doc.id}`;
            html += `
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <label class="me-2" style="white-space: nowrap;">${name}</label>
                        <input type="file" id="${inputId}" name="documents[${doc.id}]" class="form-control" accept="image/*,application/pdf">
                    </div>
                    <div class="mt-2">
                        <img src="" id="${previewId}" alt="${name}" class="img-thumbnail" style="max-width: 100px; display: none; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#previewModal" data-title="${name}">
                    </div>
                </div>
            `;
            if ((index + 1) % 3 === 0) {
                html += '</div><div class="row">';
            }
        });
        html += '</div>';
    } else {
        html = '<p class="text-muted">لا توجد مستندات مطلوبة لهذه المهنة.</p>';
    }
    document.getElementById('document-upload-area').innerHTML = html;
    selected.documents.forEach((doc) => {
        const input = document.getElementById(`doc-input-${doc.id}`);
        const preview = document.getElementById(`preview-${doc.id}`);
        if (input && preview) {
            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        preview.setAttribute('data-full', event.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                    preview.src = '';
                }
            });

            // فتح الصورة داخل المودال
            preview.addEventListener('click', function () {
                const fullSrc = preview.getAttribute('data-full');
                const title = preview.getAttribute('alt');
                document.getElementById('modal-preview-img').src = fullSrc;
                document.getElementById('modal-title').textContent = title;
            });
        }
    });
});
</script>
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
    previewImage("providerInput", "providerPreview");
</script>
<script>
    Dropzone.autoDiscover = false;
let galleryDropzone = new Dropzone("#galleryDropzone", {
    url: "#", // مش هيستخدمه
    autoProcessQueue: false, // تعطيل الرفع التلقائي
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

// لما يضاف ملف، نحطه في الـ input المخفي
galleryDropzone.on("addedfile", function(file) {
    let dataTransfer = new DataTransfer();
    let galleryInput = document.getElementById('galleryInput');

    // إضافة الملفات الحالية من الـ input
    Array.from(galleryInput.files).forEach(f => dataTransfer.items.add(f));

    // إضافة الملف الجديد
    dataTransfer.items.add(file);
    galleryInput.files = dataTransfer.files;
});

// لو شال ملف من Dropzone
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
@endpush
