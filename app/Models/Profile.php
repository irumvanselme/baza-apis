<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @property mixed _id
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "title", "bio", "location", "profile_pic"
    ];

    const UPDATED_AT = null;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
