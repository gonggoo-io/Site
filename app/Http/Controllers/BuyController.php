<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buy;
use App\Models\Insert;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $insertId = $request->input('insert_id');
        $insert = Insert::withCount('buys')->findOrFail($insertId);

        if ($insert->user_id === $userId) {
            return response()->json(['message' => '자신이 등록한 공동구매에는 참여할 수 없습니다.'], 403);
        }

        if (Buy::where('user_id', $userId)->where('insert_id', $insertId)->exists()) {
            return response()->json(['message' => '이미 참여하셨습니다.'], 409);
        }
        if ($insert->buys_count >= $insert->count) {
            return response()->json(['message' => '모집이 마감되었습니다.'], 409);
        }
        $buy = Buy::create([
            'user_id' => $userId,
            'insert_id' => $insertId,
            'created_at' => now()->setTimezone('Asia/Seoul'),
            'updated_at' => now()->setTimezone('Asia/Seoul')
        ]);
        return response()->json(['success' => true, 'buy' => $buy]);
    }

    public function destroy(Request $request)
    {
        $userId = Auth::id();
        $insertId = $request->input('insert_id');
        $insert = Insert::withCount('buys')->findOrFail($insertId);
        $buy = Buy::where('user_id', $userId)->where('insert_id', $insertId)->first();
        if (!$buy) {
            return response()->json(['message' => '참여 기록이 없습니다.'], 404);
        }
        if ($insert->buys_count == $insert->count || $insert->buys_count == $insert->count - 1) {
            return response()->json(['message' => '최대 인원수 또는 최대-1에 도달하여 취소가 불가합니다.'], 403);
        }
        $buy->delete();
        return response()->json(['success' => true]);
    }

    public function show($insertId)
    {
        $userId = Auth::id();
        $buy = Buy::where('user_id', $userId)->where('insert_id', $insertId)->first();
        return response()->json(['joined' => !!$buy]);
    }
}
