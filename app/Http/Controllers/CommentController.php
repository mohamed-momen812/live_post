<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource; // resopose object
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

    /**
     * @group Comment Management
     * APIs to manage comments
    */
class CommentController extends Controller
{
     /**
     * Display a listing of comments.
     *
     * Gets a list of comments.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     */    public function index()
    {
        $pageSize = $request->page_size ?? 20; // access page_size from request query
        $comments = Comment::paginate($pageSize);

        return  CommentResource::collection($comments); // CommentResource is the response object
    }

    /**
     * Store a newly created comment in storage.
     * @bodyParam body string[] required Body of the comment. Example: ["This comment is super beautiful"]
     * @bodyParam user_id int required The author id of the comment. Example: 1
     * @bodyParam post_id int required The post id that the comment belongs to. Example: 1
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     */
    public function store(StoreCommentRequest $request, CommentRepository $repository)
    {
        $created = $repository->create($request->only([
            'body',
            'user_id',
            'post_id',
        ]));

        return new CommentResource($created);
    }

    /**
     * Display the specified comment.
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified comment in storage.
     * @bodyParam body string[] Body of the comment. Example: ["This comment is super beautiful"]
     * @bodyParam user_id int The author id of the comment. Example: 1
     * @bodyParam post_id int The post id that the comment belongs to. Example: 1
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     */
    public function update(UpdateCommentRequest $request, Comment $comment, CommentRepository $repository)
    {
        $updated = $repository->update($comment, $request->only([ // pass the old comment to repo
            'title',
            'body',
            'user_id',
            'post_id',
        ]));


        return new CommentResource($comment);

    }

    /**
     * Remove the specified comment from storage.
     * @response 200 {
        "data": "success"
     * }
     */    public function destroy(Comment $comment, CommentRepository $repository)
    {
        $deleted = $repository->forceDelete($comment);

        return new JsonResponse([
            'data'=> "success"
        ]);
    }
}
