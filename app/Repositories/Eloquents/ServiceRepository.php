<?php

namespace  App\Repositories\Eloquents;

use App\Models\{Service,Category, Provider};
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ServiceDataTable;
use Illuminate\Support\Facades\DB;
use App\Models\Concerns\{UploadDocumentTrait, UploadMedia};
class ServiceRepository implements ServiceRepositoryInterface {
    use UploadDocumentTrait, UploadMedia;
    public function index(ServiceDataTable $serviceDataTable) {
        return $serviceDataTable->render('dashboard.Admin.services.index', ['pageTitle' => 'الخدمات و مقدمى الخدمه']);
    }

    public function create() {
        $categories = Category::getCategoryOptions();
        $providers = Provider::whereStatus('approved')->get();
        return view('dashboard.Admin.services.create', [
            'pageTitle' => 'إضافة خدمه',
            'categories' => $categories,
            'providers' => $providers,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'status'      => 'required|in:active,inactive',
            'price'       => 'nullable|numeric',
            'phone'       => 'nullable|string',
            'service'     => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $service = Service::create([
                'status'     => $request->status,
                'parent_id'  => $request->parent_id,
                'price'      => $request->price,
                'phone'      => $request->phone,
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $service->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
                $service->translateOrNew($locale)->description = $request[$locale]['description'] ?? '';
                $service->translateOrNew($locale)->short_description = $request[$locale]['short_description'] ?? '';
            }
            $service->save();
            if ($request->hasFile('service')) {
                $service->uploadSingleMedia('service', $request->file('service'), $service, null, 'media', true);
            }

                if ($request->hasFile('gallery')) {
                    $this->uploadGalleryImages(
                        'service',
                        $request->file('gallery'),
                        $service,
                        'media',
                        true,
                        false,
                        'gallery',
                        false
                    );
                }

            DB::commit();
            return redirect()->route('admin.services.index')->with('success', 'تم إضافة الخدمة بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage())->withInput();
        }
    }

    public function findById($id)
    {
        return Service::with('media')->findOrFail($id);
    }

    public function updateService($id, array $data)
    {
        DB::beginTransaction();
        try {
            $service = $this->findById($id);

            $service->update([
                'status'     => $data['status'],
                'category_id'  => $data['category_id'] ?? null,
                'price'      => $data['price'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
            ]);

            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $service->translateOrNew($locale)->name = $data[$locale]['name'] ?? '';
                $service->translateOrNew($locale)->description = $data[$locale]['description'] ?? '';
                $service->translateOrNew($locale)->short_description = $data[$locale]['short_description'] ?? '';
            }

            $service->save();

            if (!empty($data['service'])) {
                $service->uploadSingleMedia('service', $data['service'], $service, null, 'media', true);
            }

            if (!empty($data['gallery'])) {
                $this->uploadGalleryImages(
                    'service',
                    $data['gallery'],
                    $service,
                    'media',
                    true,
                    false,
                    'gallery',
                    false
                );
            }

            DB::commit();
            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }



    /*public function store(Request $request) {
        $request->validate([
            'status' => 'nullable|in:active,inactive',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            $category = Category::create([
                'parent_id' => $request->parent_id,
                'status' => $request->status,
                'country_id' => $request->country_id,
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $category->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
                $category->translateOrNew($locale)->description = $request[$locale]['description'] ?? '';
                $category->translateOrNew($locale)->short_description = $request[$locale]['short_description'] ?? '';
            }
            $category->save();

            if ($request->hasFile('category')) {
                $category->uploadSingleMedia('category', $request->file('category'), $category, null, 'media', true);
            }
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'تم حفظ بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Category $category) {
        $category->load('media');
        $categories = Category::getCategoryOptions();
        $countries = Country::all();
        return view('dashboard.Admin.categories.edit', [
            'pageTitle' => 'تعديل تصنيف',
            'category' => $category,
            'categories' => $categories,
            'countries' => $countries
        ]);
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'status' => 'nullable|in:active,inactive',
            'parent_id' => 'nullable|exists:categories,id',
            'is_featured' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $category->update([
                'parent_id' => $request->parent_id,
                'status' => $request->status,
                    'country_id' => $request->country_id,
                'is_featured'  => $request->boolean('is_featured'),
            ]);

            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $category->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
                $category->translateOrNew($locale)->description = $request[$locale]['description'] ?? '';
                $category->translateOrNew($locale)->short_description = $request[$locale]['short_description'] ?? '';
            }
            $category->save();
            if ($request->hasFile('category')) {
                $category->updateSingleMedia('category', $request->file('category'), $category, null, 'media', true);
            }
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'تم حفظ التعديلات بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Category $category) {
        $subCategories = Category::where('parent_id', $category->id)->get();
        if ($subCategories->isNotEmpty()) {
            $subCategoryNames = $subCategories->pluck('name')->implode(', ');
            return redirect()->route('admin.categories.index')->with('error', 'لا يمكن حذف هذا التصنيف لأنه يحتوي على تصنيفات فرعية: ' . $subCategoryNames);
        }
        $category->deleteExistingMedia('category', $category, null, 'media', true, 'category');
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم الحذف بنجاح!');
    }*/
}
