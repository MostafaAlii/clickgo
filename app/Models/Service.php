<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UploadMedia;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
class Service extends Model implements TranslatableContract {
    use HasFactory, UploadMedia, Translatable;
    protected $fillable = ['category_id','price', 'status', 'category_id', 'provider_id', 'admin_id'];
    public $translatedAttributes = ['name', 'description', 'short_description'];

    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }
}
