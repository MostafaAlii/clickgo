<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderDocumentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'document_id',
        'attachment_id',
        'status',
        'rejection_reason',
    ];
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            'pending'  => 'قيد المراجعة',
            default    => 'غير معروف',
        };
    }
}
