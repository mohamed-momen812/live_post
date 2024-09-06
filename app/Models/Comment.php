<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

     protected $fillable = [
        'body',
        'user_id',
        'post_id',
    ];

    protected $casts = [
        "body" => "array",  // When the body field is cast to JSON in the model (protected $casts = ['body' => 'array'];), Laravel will automatically convert the array into a JSON object ({}) or array ([]) when saving it to the database.
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
