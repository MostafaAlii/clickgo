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
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
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
                        <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="{{$key}}"
                            role="tabpanel" aria-labelledby="{{$key}}-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="{{$key}}[name]"
                                        class="form-label">{{trans('dashboard/document.document_name') .
                                        ' / ' . $lang['native']}}</label>
                                    <input type="text" id="{{$key}}[name]" name="{{$key}}[name]"
                                        placeholder="{{trans('dashboard/document.document_name_placeholder') . ' / ' . $lang['native']}}"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="status" class="form-label">{{trans('dashboard/document.status')}}</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>
        </div>
        <!-- End Location -->
        <br>
        <hr>
        <button type="submit" class="btn btn-success w-100">حفظ</button>
        </form>
    </div>
    <!--begin::Body-->
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endpush
