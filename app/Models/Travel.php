<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'travel_date',
        'country',
        'city',
        'user_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

}
