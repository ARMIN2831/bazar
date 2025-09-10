<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequests\ChangePasswordRequest;
use App\Http\Requests\UserAuthRequests\CompleteProfileRequest;
use App\Http\Requests\UserAuthRequests\LoginRequest;
use App\Http\Requests\UserAuthRequests\SendOTPForgetPasswordRequest;
use App\Http\Requests\UserAuthRequests\SendOTPRequest;
use App\Http\Requests\UserAuthRequests\VerifyOTPRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{


    function userLogin(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only(['password','mobile']);


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
            'mobile' => $request->mobile,
        ];


        if (User::where('mobile', $request->mobile)->where('mobile_active', 1)->first()){
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
            'mobile_active' => 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'village_id' => $request->village_id,
            'nationalCode' => $request->nationalCode,
            'password' => bcrypt($request->password),
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
            $filePath = $user->cart_image;
            if ($request->cart_image) $filePath = uploadFile($request->cart_image);
            $userData = [
                'postal_code' => $request->postal_code,
                'cart_image' => $filePath,
                'account_number' => $request->account_number,
                'card_number' => $request->card_number,
                'iban' => $request->iban,
            ];
        }
        if ($request->type == 'customers'){
            $filePath = $user->image;
            if ($request->image) $filePath = uploadFile($request->image);
            $userData = [
                'image' => $filePath,
                'birth' => $request->birth,
                'father_name' => $request->father_name,
                'address' => $request->address,
                'email' => $request->email,
            ];
        }

        $user->update($userData);
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.profile_updated'),
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



    public function changePassword(ChangePasswordRequest $request)
    {
        $request->validated();
        $user = User::where('mobile',$request->mobile)->first();
        verifyOTPCode('mobile',$user->id,get_class($user),$request->otp);
        $user->update(['password' => bcrypt($request->password)]);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.password_changed'),
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }


    public function SendOTPForgetPassword(SendOTPForgetPasswordRequest $request)
    {
        $request->validated();

        $user = User::where('mobile',$request->mobile)->first();

        $otpCode = sendOTPCode('mobile',$user->id,get_class($user),$request->mobile);

        return response()->json([
            'otpCode' => $otpCode,
            'status' => 'error',
            'message' => trans('messages.OTP_send'),
            'remaining_time' => 120
        ]);
    }
}
