<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UploadMedia;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
class Category extends Model implements TranslatableContract {
    use HasFactory, UploadMedia, Translatable;
    protected $table = 'categories';
    protected $fillable = ['country_id','parent_id', 'status'];
    protected $appends = ['status_text'];
    protected $with = ['translations'];
    public $translatedAttributes = ['name', 'description', 'short_description'];
    protected $casts = [
        'is_featured' => 'boolean',
    ];
    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function country()
{
    return $this->belongsTo(Country::class);
}


    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive() {
        return $this->hasMany(Category::class, 'parent_id')->with('childrenRecursive');
    }

    public static function getCategoryTree($parentId = null, $status = 'active') {
        return self::byParent($parentId)
            ->active($status)
            ->select('id', 'name', 'parent_id')
            ->with(['children' => function ($query) use ($status) {
                $query->active($status)
                    ->select('id', 'name', 'parent_id');
            }])
            ->get();
    }

    public static function getCategoryOptions($parentId = null, $status = 'active', $prefix = '') {
        $categories = self::byParent($parentId)->active($status)->with('children')->get();
        $options = collect();
        foreach ($categories as $category) {
            $options->push([
                'id' => $category->id,
                'name' => $prefix . $category->name
            ]);
            if ($category->children->isNotEmpty()) {
                $subcategories = self::getCategoryOptions($category->id, $status, $prefix . '-- ');
                $options = $options->merge($subcategories);
            }
        }
        return $options->unique('id')->values();
    }


    public function scopeActive($query, $status = 'active')
    {
        return $query->whereStatus($status);
    }

    public function scopeByParent($query, $parentId = null) {
        return $query->when(!is_null($parentId), fn($q) => $q->where('parent_id', $parentId));
    }

    public static function getRootCategories($status = 'active') {
        return self::whereNull('parent_id')->active($status)->get();
    }

    public function getStatusTextAttribute() {
        return $this->status === 'active' ? 'نشط' : 'غير نشط';
    }

    public function scopeRootActive($query)
    {
        return $query->whereNull('parent_id')->whereStatus('active');
    }

    public function getNameAttribute()
    {
        return $this->translateOrDefault()->name;
    }
}
