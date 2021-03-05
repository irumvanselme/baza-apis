<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @property mixed user
 * @property mixed answers
 * @property mixed likes
 * @property mixed topics
 * @property mixed tags
 * @property mixed answersDoc
 * @property int|mixed dislikes
 * @property mixed _id
 */

class Question extends Model
{
    use HasFactory;
    protected $fillable = ["title", "body"];

    protected $hidden = ["answers","topic_ids", "tag_ids"];

    const UPDATED_AT = null;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function topics(){
        return $this->belongsToMany(Topic::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
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
