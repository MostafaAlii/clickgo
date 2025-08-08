<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderTranslation extends Model {
    use HasFactory;
    protected $table = 'provider_translations';
    protected $fillable = ['description'];
}
