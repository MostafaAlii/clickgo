<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\{UploadMedia, UploadDocumentTrait};
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
class Provider extends Model implements TranslatableContract {
    use HasFactory, UploadMedia, UploadDocumentTrait, Translatable;
    protected $table = 'providers';
    protected $fillable = [
        'profession_id',
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];
    protected $with = ['translations'];
    public $translatedAttributes = ['description'];
    protected $hidden = [
        'password',
    ];
    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function profession() {
        return $this->belongsTo(Profession::class);
    }

    public function documentStatuses() {
        return $this->hasMany(ProviderDocumentStatus::class);
    }

    public function requiredDocuments() {
        return $this->profession ? $this->profession->documents() : null;
    }

    public function attachments() {
        return $this->hasManyThrough(
            Attachment::class,
            ProviderDocumentStatus::class,
            'provider_id',    // Foreign key on provider_document_statuses
            'id',             // Local key on attachments
            'id',             // Local key on providers
            'attachment_id'   // Foreign key on provider_document_statuses
        );
    }
}