<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserRequest extends FormRequest
{

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "result" => false,
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "message" => "The given data was invalid.",
            "errors" => $validator->errors()->all()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if (request()->isMethod("POST")) {
            $checkUniqueEmail = "unique:users,email";
            $passwordRule = ['required', 'min:8'];
        } elseif (request()->isMethod("PUT") || request()->isMethod("PATCH")) {
            $checkUniqueEmail = "unique:users,email, " . $this->user->id;
            $passwordRule = [];
        }

        return [
            "name"              => ['required'],
            "email"             => ['required', $checkUniqueEmail],
            "password"          => $passwordRule,
            'role'              => ['required'],
        ];
    }

    // if password is left blank in  form, password can not be sent as null
    // protected function prepareForValidation()
    // {
    //     if ($this->password == null) {
    //         $this->request()->remove('password');
    //     }
    // }
}
