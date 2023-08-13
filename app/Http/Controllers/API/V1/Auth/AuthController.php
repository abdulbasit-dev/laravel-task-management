<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string' , 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(false, __('api.invalid_inputs'), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        $credentials = $request->all();
        //check email
        $user = User::where('phone', $credentials['phone'])->get()->first();

        //check password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->jsonResponse(false, __('auth.failed'), Response::HTTP_UNAUTHORIZED);
        }

        //check user if have uncompleted order, then show the table_no
        $branch = $user->branch;
        $userHasUncompletedOrder = Order::query()
            ->where('branch_id', $branch->id)
            ->where('user_id', $user->id)
            ->where('status', OrderStatus::PENDING)
            ->latest()
            ->value('table_number');

        //create token
        $token = $user->createToken('myapitoken')->plainTextToken;
        $user["user_token"] = $token;
        //get user role
        $user["user_role"] = $user->getRoleNames()[0] ?? null;

        $user["table_number"] = $userHasUncompletedOrder;

        return $this->jsonResponse(true, __('api.login_success'), Response::HTTP_OK, $user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->jsonResponse(true, __('api.logout_success'), Response::HTTP_OK);
    }
}
