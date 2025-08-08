<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionTranslation extends Model {
    use HasFactory;
    protected $table = 'profession_translations';
    public $timestamps = false;
    protected $fillable = ['name'];
}
