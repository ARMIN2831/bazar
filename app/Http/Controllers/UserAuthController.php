<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequests\CompleteProfileRequest;
use App\Http\Requests\UserAuthRequests\LoginRequest;
use App\Http\Requests\UserAuthRequests\SendOTPRequest;
use App\Http\Requests\UserAuthRequests\VerifyOTPRequest;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{


    function userLogin(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only(['password']);


        if (Auth::attempt($credentials)) {
            $user = User::where('mobile',$request->mobile)->first();

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.login_successful'),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => trans('messages.invalid_credentials')
        ], 401);
    }


    public function sendOTP(SendOTPRequest $request)
    {
        $request->validated();
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'province' => $request->province,
            'city' => $request->city,
            'village' => $request->village,
            'nationalCode' => $request->nationalCode,
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
        ];


        if (User::where('mobile', $request->mobile)->where(function ($query) {$query->where('mobile_active', 1);})->first()){
            return response()->json([
                'status' => 'error',
                'message' => trans('messages.user_exists'),
            ], 422);
        }

        $user = User::firstOrCreate(
            [
                'mobile_active' => 0,
                'mobile' => $request->mobile,
            ], $userData
        );
        $otpCode = sendOTPCode('mobile',$user->id,User::class,$user->mobile);
        return response()->json([
            'otpCode' => $otpCode,
            'status' => 'success',
            'message' => trans('messages.OTP_send'),
            'remaining_time' => 120
        ]);
    }


    public function verifyOtp(VerifyOTPRequest $request)
    {
        $request->validated();

        $user = User::where('mobile', $request->mobile)->firstOrFail();
        verifyOTPCode('mobile',$user->id,User::class,$request->code);
        $user->update([
            'mobile_active' => 1
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.registration_successful'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }


    public function completeProfile(CompleteProfileRequest $request)
    {
        $request->validated();
        $user = $request->user();
        $userData = [
            'type' => $request->type,
        ];
        if ($request->type == 'providers'){
            $userData = [
                'postal_code' => $request->postal_code,
                'cart_image' => $request->cart_image,
                'account_number' => $request->account_number,
                'card_number' => $request->card_number,
                'iban' => $request->iban,
            ];
        }
        if ($request->type == 'customers'){
            $userData = [
                'image' => $request->image,
                'birth' => $request->birth,
                'father_name' => $request->father_name,
                'address' => $request->address,
                'email' => $request->email,
            ];
        }

        $user->update($userData);
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.registration_successful'),
        ], 200);
    }


    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'success',
            'user' => $user,
        ]);
    }


    /*public function changePassword(ChangePasswordRequest $request)
    {
        $request->validated();
        $email_mobile = $request->email_mobile;
        $isEmail = filter_var($email_mobile, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'mobile';
        $model = match($request->type) {
            'doctors' => Doctor::class,
            'patients' => Patient::class,
            'translators' => Translator::class,
            'drivers' => Driver::class,
        };
        $user = $model::where($field,$email_mobile)->first();
        verifyOTPCode($field,$user->id,get_class($user),$request->otp);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.password_changed')
        ]);
    }


    public function SendOTPForgetPassword(SendOTPForgetPasswordRequest $request)
    {
        $request->validated();
        $email_mobile = $request->email_mobile;
        $isEmail = filter_var($email_mobile, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'mobile';

        $model = match($request->type) {
            'doctors' => Doctor::class,
            'patients' => Patient::class,
            'translators' => Translator::class,
            'drivers' => Driver::class,
        };

        $user = $model::where($field,$email_mobile)->first();

        sendOTPCode($field,$user->id,get_class($user),$email_mobile);

        return response()->json([
            'status' => 'error',
            'message' => trans('messages.OTP_send'),
            'remaining_time' => 120
        ]);
    }*/
}
