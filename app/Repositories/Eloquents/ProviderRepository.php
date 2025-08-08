<?php

namespace  App\Repositories\Eloquents;

use App\Models\{Provider, Profession};
use App\Repositories\Contracts\ProviderRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ProviderDataTable;
use Illuminate\Support\Facades\DB;
use App\Models\Concerns\{UploadDocumentTrait, UploadMedia};
class ProviderRepository implements ProviderRepositoryInterface {
    use UploadDocumentTrait, UploadMedia;
    public function index(ProviderDataTable $providerDataTable)
    {
        return $providerDataTable->render('dashboard.Admin.providers.index', ['pageTitle' => 'مزودى الخدمه']);
    }

    public function create()
    {
        $professions = Profession::where('status', 'active')
            ->with(['documents' => function ($query) {
                $query->where('status', 'active')->with('translations');
            }])
            ->get();
        return view('dashboard.Admin.providers.create', [
            'pageTitle' => 'إضافة مزود خدمه',
            'professions' => $professions,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:providers,email',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'required|string|min:6',
            'profession_id' => 'required|exists:professions,id',
        ]);
        try {
            DB::beginTransaction();

            // 1. Create provider
            $provider = Provider::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'password'      => bcrypt($request->password),
                'profession_id' => $request->profession_id,
                'status'        => 'pending',
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $provider->translateOrNew($locale)->description = $request[$locale]['description'] ?? '';
            }
            $provider->save();
            if ($request->hasFile('provider')) {
                $provider->uploadSingleMedia('provider', $request->file('provider'), $provider, null, 'media', true);
            }
            // 2. Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $documentId => $file) {
                    $this->uploadProviderDocument(
                        $file,
                        $provider->email,
                        $provider->id,
                        $documentId
                    );
                }
            }

            if ($request->hasFile('gallery')) {
                $this->uploadGalleryImages(
                    'provider',
                    $request->file('gallery'),
                    $provider,
                    'media',
                    true,
                    false,
                    'gallery',
                    false
                );
            }
            DB::commit();
            return redirect()->route('admin.providers.index')->with('success', 'تم إضافة مزود الخدمة بنجاح');
         } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    public function uploadGallery(Request $request)
    {
        $provider = Provider::findOrFail($request->provider_id);

        $this->uploadGalleryImages(
            'providers',
            $request->file('gallery'),
            $provider,
            'media',
            false,
            false,
            'gallery',
            false
        );
        return response()->json(['message' => 'تم رفع الصور بنجاح']);
    }
}

    /*public function store(Request $request)
    {
        $request->validate([
            'status'      => 'required|in:active,inactive',
        ]);
        DB::beginTransaction();
        try {
            $profession = Document::create([
                'status'     => $request->status,
                'admin_id' => get_user_data()?->id,
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $profession->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
            }
            $profession->save();
            DB::commit();
            return redirect()->route('admin.documents.index')->with('success', 'تم إضافة المستند بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('dashboard.Admin.documents.edit', [
            'pageTitle' => 'تعديل مستند' . ' ' . $document?->name,
            'document' => $document,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {
            $document = Document::findOrFail($id);
            $document->update([
                'status' => $request->status,
            ]);

            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $document->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
            }

            $document->save();
            DB::commit();

            return redirect()->route('admin.documents.index')->with('success', 'تم تحديث المستند بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(int $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return redirect()->route('admin.documents.index')->with('success', 'تم الحذف بنجاح!');
    }*/
