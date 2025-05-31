<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Gonggoo (공구) API",
 *     version="0.1",
 *     description="📦 학교 및 회사를 위한 공동 구매 서비스, 공구 API"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/test",
     *     summary="Swagger를 테스트 해봅니다.",
     *     @OA\Response(
     *         response=200,
     *         description="Swagger + API 테스트 성공",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hello API!")
     *         )
     *     )
     * )
     */
    public function test()
    {
        return response()->json([
            'message' => 'Hello API!',
            'status' => 'success'
        ]);
    }
}