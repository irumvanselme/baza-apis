<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @property mixed questions
 */
class Topic extends Model
{
    use HasFactory;
    protected $fillable = ["name", "description"];
    protected $hidden = ["question_ids"];

    public $timestamps = false;

    public function questions(){
        return $this->belongsToMany(Question::class);
    }
}
