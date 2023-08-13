<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ['required', 'email']
        ]);

        if ($validator->fails()) {
            return $this->josnResponse(false, "The given data was invalid.", Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return $this->josnResponse(false, "Could not find this email", Response::HTTP_NOT_FOUND);
        }
        //generate otp
        $otp =  rand(100000, 999999);

        try {
            // send mail to  user
            Mail::to($user)->send(new ForgotPassword($user->name, $otp));

            $user->otp = $otp;
            $user->save();
            return $this->josnResponse(true, "Otp Send to your email");
        } catch (\Exception $e) {
            return $this->josnResponse(false, "Internal server error", Response::HTTP_INTERNAL_SERVER_ERROR, null, $e->getMessage());
        }
    }

    public function forgotPasswordVerify(Request $request)
    {
        //validation 
        $validator = Validator::make($request->all(), [
            "email" => ['required', 'email'],
            "otp" => ['required'],
            "password" => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->josnResponse(false, "The given data was invalid.", Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        $user = User::whereEmail($request->email)->first();

        //check user
        if (!$user) {
            return $this->josnResponse(false, "Could not find this email", Response::HTTP_NOT_FOUND);
        }

        if ($request->otp != $user->otp) {
            return $this->josnResponse(false, "Otp dose not match", Response::HTTP_NOT_FOUND);
        }

        $user->password = bcrypt($request->password);
        $user->otp = null;
        $user->save();

        return $this->josnResponse(true, "Password reseted successfuly", Response::HTTP_OK);
    }
}
