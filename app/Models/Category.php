<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
class Category extends Model {
    use HasFactory, HasUuid, SoftDeletes,Translatable;
    protected $table ='categories';
    protected $fillable = ['uuid','parent_id', 'status'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['name'];
    protected $hidden = ['translations'];

    public function scopeParent($query){
        return $query->whereNull('parent_id');
    }

    public function scopeChild($query){
        return $query->whereNotNull('parent_id');
    }

    public function _parent(){
        return $this->belongsTo(self::class, 'parent_id');
    }

}
