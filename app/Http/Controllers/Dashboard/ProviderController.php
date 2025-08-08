<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ProviderDataTable;
use App\Repositories\Contracts\ProviderRepositoryInterface;
use App\Models\{Provider,
    ProviderDocumentStatus};
use App\Models\Concerns\{UploadDocumentTrait, UploadMedia};
class ProviderController extends Controller
{
    use UploadDocumentTrait, UploadMedia;
    public function __construct(protected ProviderDataTable $providerDataTable, protected ProviderRepositoryInterface $providerInterface)
    {
        $this->providerInterface = $providerInterface;
        $this->providerDataTable = $providerDataTable;
    }

    public function index(ProviderDataTable $providerDataTable)
    {
        return $this->providerInterface->index($this->providerDataTable);
    }

    public function create()
    {
        return $this->providerInterface->create();
    }

    public function store(Request $request)
    {
        return $this->providerInterface->store($request);
    }

    public function show($id) {
        $provider = Provider::with([
            'profession',
            'documentStatuses.document',
            'documentStatuses.attachment',
            'media'
        ])->findOrFail($id);
        $pageTitle = $provider?->name;
        $galleryImages = $this->getGalleryMediaUrls(
            'provider',
            $provider,
            'media',       // اسم العلاقة
            'gallery'      // collection name
        );
        $mainImage = $this->getMediaUrl('provider', $provider, null, 'media', 'provider');
        return view('dashboard.Admin.providers.show', compact('provider', 'pageTitle', 'mainImage', 'galleryImages'));
    }

    public function editGalleryImage($id, $image)
    {
        $provider = Provider::findOrFail($id);
        return view('dashboard.Admin.providers.edit_gallery', compact('provider', 'image'));
    }




    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $status = ProviderDocumentStatus::findOrFail($id);
        $status->status = $request->status;
        $status->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $status->save();

        return back()->with('success', 'تم تحديث حالة المستند.');
    }

    public function approveAllDocuments(Provider $provider)
    {
        foreach ($provider->documentStatuses as $documentStatus) {
            $documentStatus->status = 'approved'; // خلي بالك من spelling بدل accepted
            $documentStatus->rejection_reason = null;
            $documentStatus->save();
        }

        return back()->with('success', 'تمت الموافقة على جميع المستندات بنجاح.');
    }

    public function reuploadDocument(Request $request, ProviderDocumentStatus $status)
    {
        $request->validate([
            'new_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480', // 20MB max
        ]);

        try {
            $provider = $status->provider;
            $identifier = $provider->email ?? $provider->phone ?? 'provider'; // تحديد الـ identifier
            $this->updateProviderDocument($request->file('new_document'), $status, $identifier);

            return redirect()->back()->with('success', 'تم إعادة رفع المستند بنجاح، وسيتم مراجعته.');
        } catch (\Exception $e) {
            \Log::error('Error reuploading document: ' . $e->getMessage());

            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع المستند، يرجى المحاولة لاحقاً.');
        }
    }

    public function deleteGallery($id, $image)
    {
        $provider = Provider::findOrFail($id);

        $this->deleteGalleryImage(
            'provider',
            $provider,
            $image,
            'media',
            'gallery'
        );

        return response()->json(['message' => 'تم حذف الصورة بنجاح']);
    }

    public function updateGallery(Request $request, $id, $image)
    {
        $request->validate([
            'new_image' => 'required|image|max:2048'
        ]);

        $provider = Provider::findOrFail($id);

        $this->updateGalleryImage(
            'provider',
            $request->file('new_image'),
            $provider,
            $image,
            'media',
            true,
            false,
            'gallery',
            false
        );
        return response()->json(['message' => 'تم تحديث الصورة بنجاح']);
    }

    public function toggleStatus($id)
    {
        $provider = Provider::findOrFail($id);

        $provider->status = $provider->status === 'approved' ? 'pending' : 'approved';
        $provider->save();

        return response()->json(['message' => 'تم تحديث الحالة بنجاح']);
    }
}
