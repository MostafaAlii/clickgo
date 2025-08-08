@extends('dashboard.layouts.master')

@section('css')

@endsection

@section('pageTitle')
{{$PageTitle}}
@endsection


@section('breadcrumbs')
@parent
<x-dashboard.breadcrumb-item route="admin.country.index" title="{{ $PageTitle }}" />
@endsection


@section('content')
@include('dashboard.layouts.common._partial.messages')
<div id="kt_content_container" class="container-xxl">
    <div class="mb-5 card card-xxl-stretch mb-xl-8">
        <!--begin::Header-->
        <div class="pt-5 border-0 card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="mb-1 card-label fw-bolder fs-3">{{$PageTitle}}</span>
                <span class="mt-1 text-muted fw-bold fs-7">{{$PageTitle}}</span>
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover">
                    <button type="button" class="btn btn-sm btn-light btn-active-primary" data-toggle="modal"
                        data-bs-target="#addNewCountry">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                    transform="rotate(-90 11.364 20.364)" fill="black" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                            </svg>
                        </span>
                        {{ trans('dashboard/country.add_country') }}
                    </button>
                </div>
            </h3>
        </div>
        @php
            $locales = LaravelLocalization::getSupportedLocales();
        @endphp
        <!-- Modal -->
        <div class="modal fade" id="addNewCountry" tabindex="-1" aria-labelledby="addNewCountryLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{-- route('admin.country.store') --}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addNewCountryLabel">{{ trans('dashboard/country.add_country') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="langTabs" role="tablist">
                                @foreach ($locales as $localeCode => $properties)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if(app()->getLocale() == $localeCode) active @endif"
                                        id="tab-{{ $localeCode }}" data-bs-toggle="tab" data-bs-target="#lang-{{ $localeCode }}"
                                        type="button" role="tab" aria-controls="lang-{{ $localeCode }}"
                                        aria-selected="{{ app()->getLocale() == $localeCode ? 'true' : 'false' }}">
                                        {{ $properties['native'] }}
                                    </button>
                                </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-3">
                                @foreach ($locales as $localeCode => $properties)
                                <div class="tab-pane fade @if(app()->getLocale() == $localeCode) show active @endif"
                                    id="lang-{{ $localeCode }}" role="tabpanel" aria-labelledby="tab-{{ $localeCode }}">

                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('dashboard/country.country_name') }} ({{ $localeCode
                                            }})</label>
                                        <input type="text" name="name[{{ $localeCode }}]" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            {{ trans('dashboard/country.country_description') }} ({{ $localeCode }})
                                        </label>
                                        <textarea name="description[{{ $localeCode }}]" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ trans('dashboard/country.country_image') }}</label>
                                <input type="file" name="country" class="form-control" accept="image/*" onchange="previewCountryImage(event)">
                                <div class="mt-2">
                                    <img id="countryImagePreview" src="#" alt="preview" style="max-height: 150px; display: none;"
                                        class="img-thumbnail">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ trans('dashboard/general.save') }}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('dashboard/general.cancel')
                                }}</button>
                        </div>
                    </div>
                </form>
            </div>
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
    @endsection

    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            const button = document.querySelector('button[data-bs-target="#addNewCountry"]');
            button.addEventListener('click', () => {
            $('#addNewCountry').modal('show');
            });
        });
        function previewCountryImage(event) {
            const input = event.target;
            const preview = document.getElementById('countryImagePreview');
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
        $(document).on('click', '.edit-country-btn', function () {
            let id = $(this).data('id');
            $.get(`/admin/country/${id}/edit`, function (res) {
                let formId = `#editCountryForm${id}`;
                let imagePreviewId = `#editCountryImagePreview${id}`;
                $(formId).attr('action', `/admin/country/${id}`);
                $(formId).append('<input type="hidden" name="_method" value="PUT">');
                @foreach ($locales as $localeCode => $properties)
                    $(`${formId} #edit_name_{{ $localeCode }}`).val(res.country.translations.find(t => t.locale === '{{ $localeCode }}')?.name || '');
                    $(`${formId} #edit_description_{{ $localeCode }}`).val(res.country.translations.find(t => t.locale === '{{ $localeCode }}')?.description || '');
                @endforeach
                if (res.image_url) {
                    $(imagePreviewId).attr('src', res.image_url).show();
                }
                $(`#editCountryModal${id}`).modal('show');
            });
        });

    </script>


    @endpush
