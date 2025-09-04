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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'province_id' => 'required|string|exists:provinces,id',
            'city_id' => 'required|string|exists:cities,id',
            'village_id' => 'required|string|exists:villages,id',
            'nationalCode' => 'required|string|unique:nationalCode,users',
            'mobile' => 'required|unique:mobile,users',
            'password' => 'required|string',
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
