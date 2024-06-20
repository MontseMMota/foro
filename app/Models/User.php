<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'singer',
        'hobby',
        'role', 
        'avatar',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }


    public function isVerified()
    {
        return $this->is_verified; // El atributo `is_verified` debe ser `true` para usuarios verificados
    }

    public function markAsVerified()
    {
        $this->update(['is_verified' => true]);
    }


    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
        ->withPivot('status')
        ->withTimestamps();
    }

    // Verificar si un usuario es amigo de otro
    public function isFriendWith($userId)
    {
        return $this->friends()->where('friends.friend_id', $userId)->exists();
    }

    public function friendsPosts()
    {
        return $this->hasManyThrough(
            Post::class,        // Modelo del post
            Friend::class,      // Modelo de la tabla intermedia (amigos)
            'user_id',          // Clave foránea del modelo User en la tabla friends
            'user_id',          // Clave foránea del modelo Post en la tabla friends
            'id',               // Clave primaria del modelo User
            'friend_id'         // Clave primaria del modelo Post
        )->where('status', 'accepted'); ;
    }


    public function isFriendAccepted($friendId)
{
    return $this->friends()->where('friend_id', $friendId)->where('status', 'accepted')->exists();
}


    public function pendingFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')
                    ->where('status', Friend::STATUS_PENDING);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }



    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    
}


