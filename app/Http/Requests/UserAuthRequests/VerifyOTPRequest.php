<?php

namespace App\Http\Requests\UserAuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class VerifyOTPRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'code' => 'required',
            'mobile' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'province_id' => 'required|string|exists:provinces,id',
            'city_id' => 'required|string|exists:cities,id',
            'village_id' => 'required|string|exists:villages,id',
            'nationalCode' => 'required|string',
            'password' => 'required|string|min:8',
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
