<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

// implements MustVerifyEmail
class User extends Authenticatable implements MustVerifyEmail
{
    // TwoFactorAuthenticatable to enable 2FA, Notifiable to enable notification
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // no more cast property
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comments(){
        return $this->hasMany(related:Comment::class, foreignKey:"user  _id", localKey:"id");
    }

    public function posts(){
        return $this->belongsToMany(related: Post::class); // no need for all arguments
    }
}
