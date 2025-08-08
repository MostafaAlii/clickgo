<div class="d-flex justify-content-center">
    <!-- Edit Button -->
    <button type="button" class="mx-1 btn btn-primary btn-sm edit-country-btn" data-bs-toggle="modal"
        data-bs-target="#editCountryModal{{ $country->id }}" data-id="{{ $country->id }}">
        <i class="fas fa-edit"></i>
    </button>

    <!-- Delete Button (Optional) -->
    <button type="button" class="mx-1 btn btn-danger btn-sm" data-bs-toggle="modal"
        data-bs-target="#deleteModal{{ $country->id }}">
        <i class="fas fa-trash"></i>
    </button>

    <div class="modal fade" id="deleteModal{{ $country->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="text-center modal-body">
                    <p>هل أنت متأكد من حذف "<strong>{{ $country->name }}</strong>"؟</p>
                    <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form action="{{-- route('admin.country.destroy', $country->id) --}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">نعم، حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCountryModal{{ $country->id }}" tabindex="-1" aria-labelledby="editCountryModalLabel" aria-hidden="true">
        <form id="editCountryForm{{ $country->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('dashboard/country.edit_country') . ' ' . $country?->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                        $locales = LaravelLocalization::getSupportedLocales();
                        @endphp
                        <ul class="nav nav-tabs" id="editLangTabs{{ $country->id }}" role="tablist">
                            @foreach ($locales as $localeCode => $properties)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(app()->getLocale() == $localeCode) active @endif"
                                    id="edit-tab-{{ $localeCode }}-{{ $country->id }}" data-bs-toggle="tab"
                                    data-bs-target="#edit-lang-{{ $localeCode }}-{{ $country->id }}" type="button" role="tab"
                                    aria-controls="edit-lang-{{ $localeCode }}-{{ $country->id }}"
                                    aria-selected="{{ app()->getLocale() == $localeCode ? 'true' : 'false' }}">
                                    {{ $properties['native'] }}
                                </button>
                            </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3" id="editLangTabsContent{{ $country->id }}">
                            @foreach ($locales as $localeCode => $properties)
                            <div class="tab-pane fade @if(app()->getLocale() == $localeCode) show active @endif"
                                id="edit-lang-{{ $localeCode }}-{{ $country->id }}" role="tabpanel"
                                aria-labelledby="edit-tab-{{ $localeCode }}-{{ $country->id }}">
                                <div class="mb-3">
                                    <label class="form-label">{{ trans('dashboard/country.country_name') }} ({{ $localeCode
                                        }})</label>
                                    <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                                        id="edit_name_{{ $localeCode }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ trans('dashboard/country.country_description') }} ({{
                                        $localeCode }})</label>
                                    <textarea name="description[{{ $localeCode }}]" rows="4" class="form-control"
                                        id="edit_description_{{ $localeCode }}"></textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('dashboard/country.country_image') }}</label>
                            <input type="file" name="country" class="form-control"
                                onchange="previewEditCountryImage(event)">
                            <div class="mt-2">
                                <img id="editCountryImagePreview{{ $country->id }}" src="#" alt="preview"
                                    style="max-height: 150px; display: none;" class="img-thumbnail">
                            </div>
                        </div>

                        <input type="hidden" id="editCountryId">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('dashboard/general.save') }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                            trans('dashboard/general.cancel') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
