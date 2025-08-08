<?php

namespace  App\Repositories\Eloquents;

use App\Models\{Document};
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\DocumentDataTable;
use Illuminate\Support\Facades\DB;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function index(DocumentDataTable $documentDataTable)
    {
        return $documentDataTable->render('dashboard.Admin.documents.index', ['pageTitle' => 'مستندات المهن']);
    }

    public function create()
    {
        return view('dashboard.Admin.documents.create', [
            'pageTitle' => 'إضافة مستند',
        ]);
    }

    public function store(Request $request)
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
    }
}
