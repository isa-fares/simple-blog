<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return UserResource::collection($this->userService->getAll());
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return new UserResource($user);
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $updated = $this->userService->update($user, $request->validated());
        return new UserResource($updated);
    }

    public function destroy($id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $this->userService->delete($user);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
