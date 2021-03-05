<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @property mixed comments
 * @property mixed user
 */
class Answer extends Model
{
    use HasFactory;
    protected $fillable = ["user_id","body"];

    const UPDATED_AT = null;

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function actions(){
        return $this->hasMany(Action::class);
    }

    public function up_votes(): int
    {
        return $this->actions()->where("action", "=", 1)->count();
    }

    public function down_votes(): int
    {
        return $this->actions()->where("action", "=", 0)->count();
    }
}
