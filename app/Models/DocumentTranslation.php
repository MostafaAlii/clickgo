<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTranslation extends Model {
    use HasFactory;
    protected $table = 'document_translations';
    public $timestamps = false;
    protected $fillable = ['name'];
}
