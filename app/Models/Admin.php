<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable {
    use HasFactory, Notifiable;
    protected $table = 'admins';
    protected $fillable = ['name','email','password','phone', 'link_password_protection', 'status', 'type', 'link_password_status'];
    protected $hidden = ['password','remember_token',];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'link_password_status' => 'boolean',
        'status' => 'string',
        'type' => 'string',
    ];
    public function profile(): HasOne {
        return $this->hasOne(related:AdminProfile::class, foreignKey:'admin_id');
    }
}
