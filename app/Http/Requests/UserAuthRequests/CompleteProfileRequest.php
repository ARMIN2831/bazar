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
        $rules = [
            'type' => 'required|in:providers,customers',
        ];

        if ($this->type === 'providers') {
            $rules['postal_code'] = 'required|string';
            $rules['cart_image'] = 'required';
            $rules['account_number'] = 'required|unique:account_number,users';
            $rules['card_number'] = 'required|unique:card_number,users';
            $rules['iban'] = 'required|string|unique:iban,users';
        }
        if ($this->type === 'customers') {
            $rules['image'] = 'required';
            $rules['birth'] = 'required';
            $rules['father_name'] = 'required|string';
            $rules['address'] = 'required|string';
            $rules['email'] = 'required|unique:email,users';
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
