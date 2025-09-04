<?php

use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Mail;

if (!function_exists('uploadFile')) {
    function uploadFile($file)
    {
        $filename = uniqid() . '_' . hash_file('md5', $file) . '.' . $file->getClientOriginalExtension();
        $uploadPath = base_path('../public_html/api.arabi-ca.com/uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $file->move($uploadPath, $filename);
        return 'https://api.arabi-ca.com/uploads/' . $filename;
    }
}


if (!function_exists('deleteUploadedFile')) {
    function deleteUploadedFile($fileUrl)
    {
        $baseUrl = 'https://api.arabi-ca.com/uploads/';
        if (strpos($fileUrl, $baseUrl) !== 0) return false;
        $relativePath = str_replace($baseUrl, '', $fileUrl);
        $uploadPath = base_path('../public_html/api.arabi-ca.com/uploads');
        $filePath = $uploadPath . '/' . $relativePath;
        if (file_exists($filePath)) return unlink($filePath);
        return false;
    }
}


if (!function_exists('sendOTPCode')) {
    function sendOTPCode($type,$userId,$userModel,$userField)
    {
        $existingOtp = UserNotification::where('model_id', $userId)
            ->where('model_type', $userModel)
            ->where('type', $type)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        if ($existingOtp) {
            $remainingTime = Carbon::now()->diffInSeconds($existingOtp->expires_at);
            throw new HttpResponseException(
                response()->json([
                   'status' => 'error',
                   'message' => trans('messages.OTP_is_valid'),
                   'remaining_time' => round($remainingTime)
               ], 429)
            );
        }


        $otpCode = rand(10000, 99999);
        if ($type == "email"){
            Mail::raw('Your verify code: ' . $otpCode, function ($message) use ($userField) {
                $message->to($userField)
                    ->subject('Verify Email');
            });
        }
        if ($type == "mobile"){
            //
        }
        UserNotification::updateOrCreate(
            [
                'model_type' => $userModel,
                'model_id' => $userId,
                'type' => $type,
            ], [
                'type' => $type,
                'message' => $otpCode,
                'model_type' => $userModel,
                'model_id' => $userId,
                'expires_at' => Carbon::now()->addMinutes(2),
            ]
        );
        return $otpCode;
    }
}


if (!function_exists('verifyOTPCode')) {
    function verifyOTPCode($type,$userId,$userModel,$code)
    {
        $otpRecord = UserNotification::where('type', $type)
            ->where('model_id',$userId)
            ->where('model_type',$userModel)
            ->first();

        if (!$otpRecord) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => trans('messages.otp_not_found'),
                ], 404)
            );
        }
        if (Carbon::now()->gt($otpRecord->expires_at)) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => trans('messages.otp_expired'),
                ], 400)
            );
        }
        if ($code != $otpRecord->message) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => trans('messages.otp_invalid'),
                ], 400)
            );
        }
        $otpRecord->delete();
    }
}
