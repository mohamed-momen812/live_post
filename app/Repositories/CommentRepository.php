<?php


namespace App\Repositories;

use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;
use App\Exceptions\GeneralException;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{


    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = Comment::query()->create([
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id'),
            ]);

            throw_if(!$created, GeneralException::class, 'Failed to create comment.');

            event(new CommentCreated($created));

            return $created;
        });
    }

    /**
     * @param Comment $comment
     * @param array $attributes
     * @return mixed
     */
    public function update($comment, array $attributes) // $comment to access to the old comment
    {
        return DB::transaction(function () use($comment, $attributes) {
            $updated = $comment->update([
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'post_id' => data_get($attributes, 'post_id', $comment->post_id),
            ]);

            throw_if(!$updated, GeneralException::class, 'Failed to update model.');

            event(new CommentUpdated($comment));

            return $comment;
        });
    }

    /**
     * @param Comment $comment
     * @return mixed
     */
    public function forceDelete($comment)
    {
        return DB::transaction(function () use($comment) {
            $deleted = $comment->forceDelete();

            throw_if(!$deleted, GeneralException::class, 'Failed to delete model.');

            event(new CommentDeleted($comment));

            return $deleted;
        });



    }
}
