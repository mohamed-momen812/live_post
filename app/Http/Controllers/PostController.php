<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Mail\WelcomeMail;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use App\Rules\IntegerArray;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * @group Post Management
 *
 * APIs to manage the post resource.
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * Gets a list of posts.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     */
    public function index(Request $request)
    {

        $pageSize = $request->page_size ?? 20; // access page_size from request query
        $posts = Post::paginate($pageSize);
        return PostResource::collection($posts);


    }

    /**
     * Store a newly created post in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     */
    public function store(StorePostRequest $request, PostRepository $repository)
    {

        // can use this validate here or in the request, the validator give us more methods
        /*

            $payload = $request->only([ // only to access to a specific key and value of the request
            'title',
            'body',
            'user_ids'
            ]);

            $validator = Validator::make($payload, [
                'title' => ['required', 'string'],
                'body' => ['required', 'string'],
                'user_ids' => [
                    'required',
                    'array',
                    new IntegerArray("momen") // rule class, don't forget the macros
                ],
            ], [
                'body.required' => 'Please enter a value for body',
                'title.string' => 'Heyyy use a string',
            ]);
        */

        // all validation in the StorePostRequest

        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new PostResource($created);
    }

    /**
     * Display the specified post.
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified post in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     */
    public function update(UpdatePostRequest $request, Post $post, PostRepository $repository)
    {
        $updated = $repository->update($post, $request->only([ // rerunt bool
            'title',
            'body',
            'user_ids',
        ]));

        return new PostResource($post); // Remmber not $updated
    }

     /**
     * Remove the specified post from storage.
     * @response 200 {
        "data": "success"
     * }
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->forceDelete($post);
        return new JsonResponse([
            'data' => 'success'
        ]);
    }
}
