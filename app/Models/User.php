<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'address'
    ];

    protected $appends = [
        'url_photo'
    ];

    public function getUrlPhotoAttribute()
    {
        return asset('storage/photos/'.$this->photo);
    }

    public function travels()
    {
        return $this->hasMany(Travel::class, 'user_email', 'email');
    }

}
