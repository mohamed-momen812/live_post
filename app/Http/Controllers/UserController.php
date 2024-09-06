<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group User Management
 *
 * APIs to manage the uer resource.
 */
class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * Gets a list of uers.
     *
     * @queryParam page_size int Size per page. Default to 20. Example:20
     * @queryParam page int Page to view. Example:1
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     */
    public function index()
    {
        $pageSize = $request->page_size ?? 20; // access page_size from request query
        $users = User::paginate($pageSize);

        return UserResource::collection($users);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @bodyParam name string required Name of the user. Example: Mohammed Mo'men
     * @bodyParam email string required Name of the user. Example: MohammedMo'men@gmail.com
     *
     */
    public function store(Request $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name',
            'email',
        ]));

        return new UserResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param \App\Models\User
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @bodyParam name string required Name of the user. Example: Mohammed Mo'men
     * @bodyParam email string required Name of the user. Example: MohammedMo'men@gmail.com
     *
     */
     public function update(Request $request, User $user, UserRepository $repository)
    {
        $updated = $repository->update($user, $request->only([
            'name',
            'email',
        ]));

        return new UserResource($user);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @response 200 {
            "data": "success"
     * }
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $deleted = $repository->forceDelete($user);

        return new JsonResponse([
            'data'=> "success"
        ]);
    }
}
