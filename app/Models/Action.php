<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = ["holder","question_id","answer_id", "action"];
}
