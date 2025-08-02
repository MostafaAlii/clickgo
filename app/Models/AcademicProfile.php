<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasUuid;
class AcademicProfile extends Model {
    use HasFactory, HasUuid;
    protected $table = 'academic_profiles';
    protected $fillable = ['name','bio','academic_id', 'uuid'];

    public function owner(): BelongsTo {
        return $this->belongsTo(related:Academic::class, foreignKey:'academic_id');
    }
}
