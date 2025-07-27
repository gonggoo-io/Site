<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KakaoOauth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class KakaoAuthController extends Controller
{
    public function redirectToKakao()
    {
        try {
            return Socialite::driver('kakao')->redirect();
        } catch (\Exception $e) {
            return redirect('/signin')->withErrors([
                'kakao' => '카카오 로그인 중 오류가 발생했습니다. 다시 시도해주세요.'
            ]);
        }
    }

    public function handleKakaoCallback()
    {
        try {
            $kakaoUser = Socialite::driver('kakao')->user();
            $kakaoId = (string) $kakaoUser->id;
            
            $kakaoOauth = KakaoOauth::where('kakao_id', $kakaoId)->first();
            
            if ($kakaoOauth) {
                Auth::login($kakaoOauth->user);
                return redirect()->intended('/dashboard');
            }
            
            $existingUser = null;
            if ($kakaoUser->email) {
                $existingUser = User::where('email', $kakaoUser->email)->first();
            }
            
            if ($existingUser) {
                KakaoOauth::create([
                    'user_id' => $existingUser->id,
                    'kakao_id' => $kakaoId,
                    'nickname' => $kakaoUser->nickname ?? $kakaoUser->name,
                    'profile_image' => $kakaoUser->avatar,
                    'email' => $kakaoUser->email,
                ]);
                
                Auth::login($existingUser);
                return redirect()->intended('/dashboard');
            }
            
            $user = User::create([
                'name' => $kakaoUser->nickname ?? $kakaoUser->name ?? '카카오사용자',
                'email' => $kakaoUser->email ?? $kakaoId . '@kakao.local',
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]);
            
            KakaoOauth::create([
                'user_id' => $user->id,
                'kakao_id' => $kakaoId,
                'nickname' => $kakaoUser->nickname ?? $kakaoUser->name,
                'profile_image' => $kakaoUser->avatar,
                'email' => $kakaoUser->email,
            ]);
            
            Auth::login($user);
            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/signin')->withErrors([
                'kakao' => '카카오 로그인 중 오류가 발생했습니다. 다시 시도해주세요.'
            ]);
        }
    }
}
