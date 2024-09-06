<?php

namespace App\Repositories;

use App\Events\Models\Post\PostCreated;
use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use App\Exceptions\GeneralException;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    public function create(array $attributes) // attributes is an array to recieve many arguments
    {
        return DB::transaction(function () use ($attributes) { // creating transaction to ensure that all db action done
                // need to pass $request to the sub function via use to access it
                $created = Post::create([
                    "title"=> data_get($attributes, "title", "Untitled"), // attributes = request
                    "body"=>  data_get($attributes, "body"), // use data_get for array of arguments
                ]);

                // if(! $created) {
                //     throw new GeneralException("Failed to create post");
                // }

                throw_if(! $created,  GeneralException::class, "Failed to create post.", 422); // Throw the given exception if the given condition is true.

                event(new PostCreated($created));

                if($userIds = data_get($attributes, 'user_ids')){ // use realtion to put in pivot table
                    $created->users()->sync($userIds);
                }

                return $created; // return thr created post from this function to pass it to the response
            });
    }

    public function update($post, array $attributes)
    {
        return DB::transaction(function () use($post, $attributes) {
            $updated = $post->update([
                'title' => data_get($attributes, 'title', $post->title),
                'body' => data_get($attributes, 'body', $post->body),
            ]);

            throw_if(! $updated,  GeneralException::class, "Failed to update post.", 422);

            event(new PostUpdated($post));

            if($userIds = data_get($attributes, 'user_ids')){
                $post->users()->sync($userIds);
            }

            return $post;

        });
    }


    public function forceDelete($post)
    {
        return DB::transaction(function () use($post) {
            $deleted = $post->forceDelete();

            throw_if(! $deleted,  GeneralException::class, "Failed to delete post.", 422);

            event(new PostDeleted($post));

            return $deleted;
        });



    }
}

