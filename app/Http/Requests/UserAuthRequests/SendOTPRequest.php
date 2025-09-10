<?php

namespace App\Http\Requests\UserAuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class SendOTPRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'mobile' => 'required',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'login failed',
                'message' => $validator->errors()->first(),
            ], 422)
        );
    }
}
