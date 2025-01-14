<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function request()
{
    $validator = Validator::make(request()->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return ResponseFormatter::error(400, $validator->errors());
    }

    $check = DB::table('password_reset_tokens')
              ->where('email', request()->email)
              ->count();

    if ($check > 0) {
        return ResponseFormatter::error(400, 'Anda sudah melakukan ini, silahkan resend OTP!');
    }

    do {
        $otp = rand(100000, 999999);
        // Perbaikan disini - ganti otp_register menjadi token
        $otpCount = DB::table('password_reset_tokens')
                     ->where('token', $otp)
                     ->count();
    } while ($otpCount > 0);

    // Perbaikan disini - ganti password_reset_tokens menjadi token
    DB::table('password_reset_tokens')->insert([
        'email' => request('email'),
        'token' => $otp,
        'created_at' => now() // tambahkan timestamp
    ]);

    $user = User::whereEmail(request()->email)->firstOrFail();
    Mail::to($user->email)->send(new \App\Mail\SendForgotPasswordOTP($user, $otp));

    return ResponseFormatter::success([
        'is_sent' => true
    ]);
}

public function resendOtp()
{
    $validator = Validator::make(request()->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return ResponseFormatter::error(400, $validator->errors());
    }

    $otpRecord = DB::table('password_reset_tokens')
                   ->where('email', request()->email)
                   ->first();

    if (is_null($otpRecord)) {
        return ResponseFormatter::error(400, 'Request tidak ditemukan');
    }

    do {
        $otp = rand(100000, 999999);
        $otpCount = DB::table('password_reset_tokens')
                     ->where('token', $otp)
                     ->count();
    } while ($otpCount > 0);

    try {
        DB::table('password_reset_tokens')
          ->where('email', request()->email)
          ->update([
              'token' => $otp,
              'created_at' => now()
          ]);

        $user = User::whereEmail(request()->email)->firstOrFail();
        Mail::to($user->email)->send(new \App\Mail\SendForgotPasswordOTP($user, $otp));

        return ResponseFormatter::success([
            'is_sent' => true
        ], 'OTP berhasil dikirim ulang');

    } catch (\Exception $e) {
        return ResponseFormatter::error(500, 'Gagal mengirim OTP: ' . $e->getMessage());
    }
}

    public function verifyOtp()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|exists:password_reset_tokens,token',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $check = DB::table('password_reset_tokens')->where('token', request()->otp)->where('email', request()->email)->count();
        if($check > 0) {
            return ResponseFormatter::success([
                'is_correct' => true
            ]);
        }

        return ResponseFormatter::error(400, 'Invalid OTP');
    }

    public function resetPassword(){
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|exists:password_reset_tokens,token',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $token = DB::table('password_reset_tokens')->where('token', request()->otp)->where('email', request()->email)->first();
        if (!is_null($token)) {
            $user = User::whereEmail(request()->email)->first();
            $user->update([
                'password' => bcrypt(request()->password)
            ]);
            DB::table('password_reset_tokens')->where('token', request()->otp)->where('email', request()->email)->delete();

            $token = $user->createToken(config('app.name'))->plainTextToken;

            return ResponseFormatter::success([
                'token' => $token
            ]);
        }
        return ResponseFormatter::success([
            'token' => $token
        ], 'change password successfully');
    }



}
