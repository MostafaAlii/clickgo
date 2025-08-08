<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UploadMedia;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
class Country extends Model implements TranslatableContract {
    use HasFactory, UploadMedia, Translatable;
    protected $table = 'countries';
    protected $fillable = [];
    public $translatedAttributes = ['name', 'description'];

    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function categories()
{
    return $this->hasMany(Category::class);
}

}