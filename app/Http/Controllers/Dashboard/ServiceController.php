<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ServiceDataTable;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Models\{Service,Category,Provider};
use App\Models\Concerns\{UploadDocumentTrait, UploadMedia};
class ServiceController extends Controller {
    use UploadDocumentTrait, UploadMedia;
    public function __construct(protected ServiceDataTable $serviceDataTable, protected ServiceRepositoryInterface $serviceInterface) {
        $this->serviceInterface = $serviceInterface;
        $this->serviceDataTable = $serviceDataTable;
    }

    public function index(ServiceDataTable $serviceDataTable) {
        return $this->serviceInterface->index($this->serviceDataTable);
    }

    public function create() {
        return $this->serviceInterface->create();
    }

    public function store(Request $request) {
        return $this->serviceInterface->store($request);
    }

    public function edit($id)
    {
        $service = $this->serviceInterface->findById($id);
        $categories = Category::getCategoryOptions();
        $providers = Provider::whereStatus('approved')->get();
        $galleryImages = $this->getGalleryMediaUrls(
            'service',
            $service,
            'media',       // اسم العلاقة
            'gallery'      // collection name
        );
        return view('dashboard.Admin.services.edit', [
            'pageTitle' => 'تعديل خدمه',
            'service' => $service,
            'categories' => $categories,
            'providers' => $providers,
            'galleryImages' => $galleryImages
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:active,inactive',
            'price'       => 'nullable|numeric',
            'service'     => 'nullable|image|max:2048',
        ]);

        try {
            $this->serviceInterface->updateService($id, $request->all());
            return redirect()->route('admin.services.index')->with('success', 'تم تعديل الخدمة بنجاح!');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التعديل: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteGallery($id, $image)
    {
        $service = Service::findOrFail($id);

        $this->deleteGalleryImage(
            'service',
            $service,
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

        $service = Service::findOrFail($id);

        $this->updateGalleryImage(
            'service',
            $request->file('new_image'),
            $service,
            $image,
            'media',
            true,
            false,
            'gallery',
            false
        );
        return response()->json(['message' => 'تم تحديث الصورة بنجاح']);
    }
}
