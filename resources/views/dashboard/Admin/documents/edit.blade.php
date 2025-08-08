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
        <div class="card-body py-3">
            <form action="{{ route('admin.documents.update', $document->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                        <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="{{ $key }}"
                            role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="{{ $key }}[name]" class="form-label">
                                        {{ trans('dashboard/profession.profession_name') . ' / ' . $lang['native'] }}
                                    </label>
                                    <input type="text" id="{{ $key }}[name]" name="{{ $key }}[name]"
                                        class="form-control" value="{{ $document->translateOrNew($key)->name }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label for="status" class="form-label">{{ trans('dashboard/document.status') }}</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ $document->status == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $document->status == 'inactive' ? 'selected' : '' }}>غير نشط
                            </option>
                        </select>
                    </div>
                </div>

                <br>
                <hr>
                <button type="submit" class="btn btn-primary w-100">تحديث</button>
            </form>
        </div>
    </div>
    <!--begin::Body-->
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endpush
