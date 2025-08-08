<?php

namespace  App\Repositories\Eloquents;

use App\Models\{Profession, Document};
use App\Repositories\Contracts\ProfessionRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ProfessionDataTable;
use Illuminate\Support\Facades\DB;

class ProfessionRepository implements ProfessionRepositoryInterface
{
    public function index(ProfessionDataTable $professionDataTable)
    {
        return $professionDataTable->render('dashboard.Admin.professions.index', ['pageTitle' => 'المهن']);
    }

    public function create() {
        $documents = Document::where('status', 'active')->get();
        return view('dashboard.Admin.professions.create', [
            'pageTitle' => 'إضافة مهنه',
            'documents' => $documents,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'status'      => 'required|in:active,inactive',
        ]);
        DB::beginTransaction();
        try {
            $profession = Profession::create([
                'status'     => $request->status,
                'admin_id' => get_user_data()?->id,
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $profession->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
            }
            $profession->save();
            if ($request->has('documents')) {
                $profession->documents()->sync($request->input('documents'));
            }
            DB::commit();
            return redirect()->route('admin.professions.index')->with('success', 'تم إضافة المهنه بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id) {
        $profession = Profession::with('documents')->findOrFail($id);
        $documents = Document::where('status', 'active')->get();
        return view('dashboard.Admin.professions.edit', [
            'pageTitle' => 'تعديل مهنه' . ' ' . $profession?->name,
            'profession' => $profession,
            'documents' => $documents,
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        DB::beginTransaction();
        try {
            $profession = Profession::findOrFail($id);
            $profession->update([
                'status' => $request->status,
            ]);
            foreach (config('laravellocalization.supportedLocales') as $locale => $lang) {
                $profession->translateOrNew($locale)->name = $request[$locale]['name'] ?? '';
            }
            $profession->save();
            $profession->documents()->sync($request->input('documents', []));
            DB::commit();
            return redirect()->route('admin.professions.index')->with('success', 'تم تحديث المهنة بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(int $id) {
        $profession = Profession::findOrFail($id);
        $profession->delete();
        return redirect()->route('admin.professions.index')->with('success', 'تم الحذف بنجاح!');
    }
}
