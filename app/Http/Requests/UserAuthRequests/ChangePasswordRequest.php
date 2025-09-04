<?php

namespace App\Http\Requests\UserAuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class ChangePasswordRequest extends FormRequest
{
    public bool $isEmail;


    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $this->isEmail = filter_var($this->email_mobile, FILTER_VALIDATE_EMAIL);
    }


    public function rules(): array
    {
        return [
            'type' => 'required|in:doctors,patients,translators,drivers',
            'email_mobile' => 'required|min:8|max:16',
            'password' => 'required',
            'otp' => 'required',
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
