<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // $this->authorize('view_user');

        $users = User::with("tasks")->get();

        return new UserCollection($users);
    }

    public function store(UserRequest $request)
    {
        // $this->authorize('add_user');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->safe()->except(['role']);

            $user = User::create($validated)->assignRole($request->role);

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('User created successfully!'), Response::HTTP_CREATED, $user);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function show(User $user)
    {
        //  $this->authorize('view_user');

        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {
        //  $this->authorize('edit_user');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->safe()->except(['role']);

            $user->update($validated);

            $user->syncRoles($request->role);

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('User updated successfully!'), Response::HTTP_OK, $user);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy(User $user)
    {
        //  $this->authorize('delete_user');

        // begin transaction
        DB::beginTransaction();
        try {
            $user->delete();

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('User deleted successfully!'), Response::HTTP_OK);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }
}
