<?php

namespace App\Http\Requests\UserAuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CompleteProfileRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $userId = $this->user()->id;
        $rules = [
            'type' => 'required|in:providers,customers',
        ];

        if ($this->type === 'providers') {
            $rules['postal_code'] = 'required|string';
            $rules['account_number'] = 'required|unique:users,account_number,'. $userId;
            $rules['card_number'] = 'required|unique:users,card_number,'. $userId;
            $rules['iban'] = 'required|string|unique:users,iban,'. $userId;
        }
        if ($this->type === 'customers') {
            $rules['birth'] = 'required';
            $rules['father_name'] = 'required|string';
            $rules['address'] = 'required|string';
            $rules['email'] = 'required|unique:users,email,'. $userId;
        }

        return $rules;
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
