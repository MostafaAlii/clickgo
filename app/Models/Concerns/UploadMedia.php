<?php
declare(strict_types=1);
namespace App\Models\Concerns;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
trait UploadMedia {
    public function uploadSingleMedia(
        $baseFolder,
        UploadedFile $file,
        $model,
        ?string $column = null,
        ?string $relation = null,
        bool $useStorage = false,
        bool $generateThumbnail = false,
        ?string $collectionName = null,
        bool $addWatermark = false
    ) {
        $disk = $useStorage ? 'local' : 'public';
        $folderPath = "/uploads/$baseFolder";
        if (!$this->isValidImage($file)) {
            throw new \Exception("الصورة غير صحيحة أو تالفة.");
        }
        if ($useStorage) {
            $publicPath = public_path($folderPath);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
        } else {
            $storagePath = storage_path("app/public/$folderPath");
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
        }
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $extension;
        $filePath = "$folderPath/$fileName";
        $image = Image::make($file->getPathname());
        if ($addWatermark) {
            $watermark = Image::make(storage_path('app/public/watermark.png'));
            $image->insert($watermark, 'bottom-right', 10, 10);
        }
        if ($useStorage) {
            $image->save(public_path($filePath));
        } else {
            $image->save(storage_path("app/public/$filePath"));
        }
        if ($generateThumbnail) {
            $this->generateThumbnail($image, $folderPath, $fileName, $useStorage);
        }
        $collectionName = $collectionName ?? array_search($file, request()->allFiles(), true) ?? 'default';
        if ($relation) {
            $media = $model->$relation()->create([
                'file_name' => $fileName,
                'disk' => $useStorage ? 'direct_public' : 'storage_public',
                'mediable_id'   => $model->id,
                'mediable_type' => get_class($model),
                'collection_name' => $collectionName,
                'type' => 'main',
            ]);
            if (!$media) {
                throw new \Exception("فشل حفظ الميديا في قاعدة البيانات.");
            }
        } elseif ($column) {
            $model->update([$column => $fileName]);
        }
        return $fileName;
    }

    public function updateSingleMedia(
        $baseFolder,
        UploadedFile $file,
        $model,
        ?string $column = null,
        ?string $relation = null,
        bool $useStorage = false,
        bool $generateThumbnail = false,
        ?string $collectionName = null,
        bool $addWatermark = false,
        string $type = 'main'
    ) {
        $this->deleteExistingMedia($baseFolder, $model, $column, $relation, $useStorage, $collectionName);
        return $this->uploadSingleMedia($baseFolder, $file, $model, $column, $relation, $useStorage, $generateThumbnail, $collectionName, $addWatermark);
    }

    public function deleteExistingMedia($baseFolder, $model, ?string $column, ?string $relation, bool $useStorage, ?string $collectionName) {
        $base = "uploads/$baseFolder";

        if ($column && in_array($column, $model->getFillable())) {
            $fileName = $model->{$column};
            if ($fileName) {
                $this->deleteFile($base, $fileName, $useStorage);
            }
        } elseif ($relation && method_exists($model, $relation)) {
            $query = $model->$relation();
            if ($collectionName) {
                $query->where('collection_name', $collectionName);
            }
            $media = $query->first();
            if ($media) {
                $this->deleteFile($base, $media->file_name, $useStorage);
                $media->delete();
            }
        }
    }

    public function deleteFile($base, $fileName, bool $useStorage) {
        $originalPath = $useStorage ? public_path("$base/$fileName") : storage_path("app/public/$base/$fileName");
        $thumbnailPath = $useStorage ? public_path("$base/thumbnails/$fileName") : storage_path("app/public/$base/thumbnails/$fileName");

        if (file_exists($originalPath))
            unlink($originalPath);


        if (file_exists($thumbnailPath))
            unlink($thumbnailPath);
    }

    private function isValidImage(UploadedFile $file) {
        try {
            $image = Image::make($file->getRealPath());
            return in_array($image->mime(), ['image/jpeg', 'image/png', 'image/webp']);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generateThumbnail($image, string $folderPath, string $fileName, bool $useStorage) {
        $thumbnailFolderPath = "$folderPath/thumbnails";
        $thumbnailPath = "$thumbnailFolderPath/$fileName";
        if ($useStorage) {
            $publicThumbnailPath = public_path($thumbnailFolderPath);
            if (!file_exists($publicThumbnailPath)) {
                mkdir($publicThumbnailPath, 0777, true);
            }
        } else {
            $storageThumbnailPath = storage_path("app/public/$thumbnailFolderPath");
            if (!file_exists($storageThumbnailPath)) {
                mkdir($storageThumbnailPath, 0777, true);
            }
        }
        $thumbnail = $image->resize(200, 200)->encode();
        if ($useStorage) {
            $thumbnail->save(public_path($thumbnailPath));
        } else {
            $thumbnail->save(storage_path("app/public/$thumbnailPath"));
        }
    }

    public function getMediaUrls($baseFolder, $model, ?string $column = null, ?string $relation = null, ?string $collectionName = null) {
        if (!$model) {
            return [];
        }
        $base = "$baseFolder/uploads/" . class_basename($model);
        $images = [];
        if ($column && in_array($column, $model->getFillable())) {
            $fileName = $model->{$column};
            if ($fileName) {
                $images['original'] = asset("{$base}/{$fileName}");
                $images['thumbnail'] = asset("{$base}/thumbnails/{$fileName}");
            }
        } elseif ($relation && method_exists($model, $relation)) {
            $query = $model->$relation();
            if ($collectionName) {
                $query->where('collection_name', $collectionName);
            }
            $media = $query->first();
            if ($media) {
                $disk = $media->disk;
                $fileName = $media->file_name;

                if ($disk === 'direct_public') {
                    $images['original'] = asset("{$base}/{$fileName}");
                    $images['thumbnail'] = asset("{$base}/thumbnails/{$fileName}");
                } elseif ($disk === 'storage_public') {
                    $images['original'] = asset("storage/{$base}/{$fileName}");
                    $images['thumbnail'] = asset("storage/{$base}/thumbnails/{$fileName}");
                }
            }
        }
        return $images;
    }

    public function getMediaUrl(
        string $baseFolder,
        $model,
        ?string $column = null,
        ?string $relation = null,
        ?string $collectionName = null
    ): ?string {
        if (!$model) return null;

        $base = "uploads/$baseFolder";
        if ($column && in_array($column, $model->getFillable())) {
            $fileName = $model->{$column};
            if ($fileName) {
                return asset("{$base}/{$fileName}");
            }
        }
        if ($relation && method_exists($model, $relation)) {
            $query = $model->$relation();
            if ($collectionName) {
                $query->where('collection_name', $collectionName);
            }
            $media = $query->first();
            if ($media) {
                $fileName = $media->file_name;
                $disk = $media->disk;
                if ($disk === 'direct_public') {
                    return asset("{$base}/{$fileName}");
                } elseif ($disk === 'storage_public') {
                    return asset("storage/{$base}/{$fileName}");
                }
            }
        }
        return null;
    }

    public function uploadGalleryImage(
        string $baseFolder,
        UploadedFile $file,
        $model,
        ?string $relation = null,
        bool $useStorage = false,
        bool $generateThumbnail = false,
        ?string $collectionName = null,
        bool $addWatermark = false
    ): string {
        $disk = $useStorage ? 'local' : 'public';
        $folderPath = "/uploads/{$baseFolder}/{$model->id}/gallery";

        // إنشاء المجلد إذا مش موجود
        if ($useStorage) {
            $publicPath = public_path($folderPath);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
        } else {
            $storagePath = storage_path("app/public/$folderPath");
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
        }

        // التحقق من الصورة
        if (!$this->isValidImage($file)) {
            throw new \Exception("الصورة غير صحيحة أو تالفة.");
        }

        // حفظ الصورة
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $extension;
        $filePath = "$folderPath/$fileName";
        $image = Image::make($file->getPathname());

        if ($addWatermark) {
            $watermark = Image::make(storage_path('app/public/watermark.png'));
            $image->insert($watermark, 'bottom-right', 10, 10);
        }

        if ($useStorage) {
            $image->save(public_path($filePath));
        } else {
            $image->save(storage_path("app/public/$filePath"));
        }

        // توليد ثومبنيل
        if ($generateThumbnail) {
            $this->generateThumbnail($image, $folderPath, $fileName, $useStorage);
        }

        // حفظ في الداتابيز
        if ($relation) {
            $media = $model->$relation()->create([
                'file_name' => $fileName,
                'disk' => $useStorage ? 'direct_public' : 'storage_public',
                'mediable_id'   => $model->id,
                'mediable_type' => get_class($model),
                'collection_name' => $collectionName ?? 'gallery',
                'type' => 'gallery',
            ]);

            if (!$media) {
                throw new \Exception("فشل حفظ الميديا في قاعدة البيانات.");
            }
        }

        return $fileName;
    }


    public function uploadGalleryImages(
        string $baseFolder,
        array $files,
        $model,
        ?string $relation = null,
        bool $useStorage = false,
        bool $generateThumbnail = false,
        ?string $collectionName = null,
        bool $addWatermark = false
    ): array {
        $uploaded = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) continue;

            $uploaded[] = $this->uploadGalleryImage(
                $baseFolder,
                $file,
                $model,
                $relation,
                $useStorage,
                $generateThumbnail,
                $collectionName,
                $addWatermark
            );
        }

        return $uploaded;
    }

    public function getGalleryMediaUrls(
        string $baseFolder,
        $model,
        ?string $relation = null,
        ?string $collectionName = null
    ): array {
        if (!$model) return [];

        $base = "uploads/$baseFolder";
        $images = [];

        if ($relation && method_exists($model, $relation)) {
            $query = $model->$relation()->where('type', 'gallery');

            if ($collectionName) {
                $query->where('collection_name', $collectionName);
            }

            $mediaItems = $query->get();

            foreach ($mediaItems as $media) {
                $fileName = $media->file_name;
                $disk = $media->disk;

                if ($disk === 'direct_public') {
                    $images[] = asset("{$base}/{$model->id}/gallery/{$fileName}");
                } elseif ($disk === 'storage_public') {
                    $images[] = asset("storage/{$base}/{$model->id}/gallery/{$fileName}");
                }
            }
        }

        return $images;
    }

    // حذف صورة من المعرض
    public function deleteGalleryImage(
        string $baseFolder,
        $model,
        string $fileName,
        ?string $relation = null
    ): bool {
        $folderPath = "/uploads/{$baseFolder}/{$model->id}/gallery";
        $storagePath = storage_path("app/public$folderPath");
        $publicPath = public_path($folderPath);

        // حذف الملف من التخزين
        if (file_exists("$storagePath/$fileName")) {
            unlink("$storagePath/$fileName");
        }
        if (file_exists("$publicPath/$fileName")) {
            unlink("$publicPath/$fileName");
        }

        // حذف السجل من قاعدة البيانات
        if ($relation && method_exists($model, $relation)) {
            $model->$relation()->where('file_name', $fileName)->delete();
        }

        return true;
    }

    // تحديث صورة في المعرض
    public function updateGalleryImage(
        string $baseFolder,
        UploadedFile $newFile,
        $model,
        string $oldFileName,
        ?string $relation = null,
        bool $useStorage = false,
        bool $generateThumbnail = false,
        ?string $collectionName = null,
        bool $addWatermark = false
    ): string {
        // حذف القديمة
        $this->deleteGalleryImage($baseFolder, $model, $oldFileName, $relation);

        // رفع الجديدة
        return $this->uploadGalleryImage(
            $baseFolder,
            $newFile,
            $model,
            $relation,
            $useStorage,
            $generateThumbnail,
            $collectionName,
            $addWatermark
        );
    }
}