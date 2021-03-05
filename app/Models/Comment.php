<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "body"];

    const UPDATED_AT = null;

    public function answer(){
        return $this->belongsTo(Answer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
