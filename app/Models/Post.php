<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
{
    return $this->hasMany(Response::class);
}

public function likes()
{
    return $this->hasMany(Like::class);
}

}