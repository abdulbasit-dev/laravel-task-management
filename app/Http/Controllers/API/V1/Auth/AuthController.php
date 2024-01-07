<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->all();
        //check email
        $user = User::where('email', $credentials['email'])->get()->first();

        //check password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->jsonResponse(false, __('auth.failed'), Response::HTTP_UNAUTHORIZED);
        }

        //create token
        $token = $user->createToken('myapitoken')->plainTextToken;
        $user["user_token"] = $token;
        //get user role
        $user["user_role"] = $user->getRoleNames()[0] ?? null;

        // don't attach roles to user
        $user->unsetRelation('roles');

        return $this->jsonResponse(true, __('Logged In Successfully!'), Response::HTTP_OK, $user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->jsonResponse(true, __('Logged Out Successfully!'), Response::HTTP_OK);
    }
}
