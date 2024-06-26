<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Response extends Model
{
    use HasFactory;

    protected $fillable = [
    'post_id', 
    'user_id', 
    'content',
    'like',
    ];

    
        public function post()
        {
            return $this->belongsTo(Post::class);
        }
    
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        public function likes()
        {
            return $this->hasMany(Like::class);
        }
    }

