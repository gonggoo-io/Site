<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\EmailVerificationCode;

class EmailVerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $code = sprintf("%06d", mt_rand(1, 999999));
        
        Cache::put('email_verification_' . $request->email, $code, now()->addMinutes(5));
        
        Mail::to($request->email)->send(new EmailVerificationCode($code));

        return response()->json([
            'message' => '인증번호가 이메일로 전송되었습니다.',
            'status' => 'success'
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        $cachedCode = Cache::get('email_verification_' . $request->email);
        
        if (!$cachedCode) {
            return response()->json([
                'message' => '인증번호가 만료되었습니다. 다시 요청해주세요.',
                'status' => 'expired'
            ], 400);
        }

        if ($cachedCode !== $request->code) {
            return response()->json([
                'message' => '인증번호가 일치하지 않습니다.',
                'status' => 'invalid'
            ], 400);
        }

        Cache::forget('email_verification_' . $request->email);
        Cache::put('email_verified_' . $request->email, true, now()->addMinutes(10));

        return response()->json([
            'message' => '이메일 인증이 완료되었습니다.',
            'status' => 'verified'
        ]);
    }
}