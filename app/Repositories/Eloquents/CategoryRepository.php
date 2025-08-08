<?php

namespace  App\Repositories\Eloquents;

use App\Models\{Category,Country};
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\CategoryDataTable;
use Illuminate\Support\Facades\DB;
class CategoryRepository implements CategoryRepositoryInterface {
    public function index(CategoryDataTable $categoryDataTable) {
        return $categoryDataTable->render('dashboard.Admin.categories.index', ['pageTitle' => 'التصنيفات']);
    }

    public function create() {
        $categories = Category::getCategoryOptions();
        $countries = Country::all();
        return view('dashboard.Admin.categories.create', [
            'pageTitle' => 'إضافة تصنيف',
            'categories' => $categories,
            'countries' => $countries,
        ]);
    }

    public function store(Request $request) {
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
    }
}
