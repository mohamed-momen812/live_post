<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts = [ // The attributes that should be cast
        "body" => "array", // When the body field is cast to JSON in the model (protected $casts = ['body' => 'array'];), Laravel will automatically convert the array into a JSON object ({}) or array ([]) when saving it to the database.
    ];

    // protected $hidden = [ // The attributes that should be hidden in the response
    //     "title",
    // ];

    protected $fillable = [ // The attributes that assignable
        "body",
        "title",
    ];

    // protected $appends = [ // The accessors to append to the model's array form in the response
    //     "title",
    // ];





    // define both an accessor and a mutator
    // Accessor used to transform row model attribute to custom value
    // Mutator used to transform row befor store in db
    // should written in camel case and use as snack case
    protected function title(): Attribute {
        return Attribute::make(
            // get: fn(string $value) => strtoupper($value), // Accessor
            // set: fn (string $value) => strtolower($value), // Mutator

        );
    }

    // creating a one to many realation between post and comments
    public function comments(){
        return $this->hasMany(related:Comment::class, foreignKey:"post_id", localKey:"id");
    }

    // Define a many-to-many relationship. between post and user
    public function users(){
        return $this->belongsToMany(
            related: User::class,
            table:'post_user',
            foreignPivotKey:'post_id',
            relatedPivotKey: 'user_id',
            parentKey: 'id',
            relatedKey: 'id'
        );
    }
}
