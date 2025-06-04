<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="인증"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     summary="회원가입",
     *     description="새로운 사용자를 등록합니다.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="홍길동"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             example={
     *                 "name": "신희성",
     *                 "email": "shinhuiseong07@gmail.com",
     *                 "password": "password123",
     *                 "password_confirmation": "password123"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="회원가입 성공",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "회원가입이 완료되었습니다.",
     *                 "user": {
     *                     "id": 1,
     *                     "name": "신희성",
     *                     "email": "shinhuiseong07@gmail.com",
     *                     "create": "2025-06-04T05:52:56.533Z",
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="유효성 검사 실패"
     *     )
     * )
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => '회원가입이 완료되었습니다.',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/signin",
     *     summary="로그인",
     *     description="사용자 로그인을 처리합니다.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             example={
     *                 "email": "shinhuiseong07@gmail.com",
     *                 "password": "password123"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="로그인 성공",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "로그인이 완료되었습니다.",
     *                 "access_token": "1|abcdef123456...",
     *                 "token_type": "Bearer",
     *                 "user": {
     *                     "id": 1,
     *                     "name": "홍길동",
     *                     "email": "user@example.com",
     *                     "created_at": "2024-03-14T12:00:00.000000Z"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="유효성 검사 실패"
     *     )
     * )
     */
    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['이메일 또는 비밀번호가 일치하지 않습니다.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '로그인이 완료되었습니다.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
