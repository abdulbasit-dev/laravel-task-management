<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        //validation 
        $validator = Validator::make($request->all(), [
            "current_password" => ['required'],
            "new_password" => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->josnResponse(false, "The given data was invalid.", Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        $user = auth()->user();
        // check if password match
        if(!Hash::check($request->current_password,$user->password)){
            return $this->josnResponse(false, "Old Password Does't match", Response::HTTP_UNAUTHORIZED);
        }

        //update password
        $user->password  = bcrypt($request->new_password);
        $user->save();

        return $this->josnResponse(true, "Password rested successfully", Response::HTTP_OK);
    }
}
