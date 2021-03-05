<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ["name", "description"];
    protected $hidden = ["question_ids"];

    public function questions(){
        return $this->belongsToMany(Question::class);
    }
}
