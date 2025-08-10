<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insert;
use App\Models\Buy;
use App\Http\Resources\InsertResource;
use App\Http\Resources\BuyResource;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        return redirect('/mypage/applied');
    }

    public function myInserts()
    {
        $userId = Auth::id();
        $inserts = Insert::with(['user', 'buys'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return InsertResource::collection($inserts);
    }

    public function myBuys()
    {
        $userId = Auth::id();
        $buys = Buy::with(['insert.user', 'insert.buys'])
            ->where('user_id', $userId)
            ->whereNull('cancelled_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return BuyResource::collection($buys);
    }

    public function debugMyBuys()
    {
        $userId = auth()->id();
        $user = auth()->user();
        $allBuys = Buy::with(['insert.user', 'insert.buys'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeBuys = Buy::with(['insert.user', 'insert.buys'])
            ->where('user_id', $userId)
            ->whereNull('cancelled_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $userInserts = Insert::with(['user', 'buys'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'user_id' => $userId,
            'user_email' => $user->email ?? 'N/A',
            'all_buys_count' => $allBuys->count(),
            'active_buys_count' => $activeBuys->count(),
            'user_inserts_count' => $userInserts->count(),
            'all_buys' => BuyResource::collection($allBuys),
            'active_buys' => BuyResource::collection($activeBuys),
            'user_inserts' => InsertResource::collection($userInserts),
        ]);
    }

    public function debugMyInserts()
    {
        $userId = auth()->id();
        $inserts = Insert::with(['user', 'buys'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'user_id' => $userId,
            'inserts_count' => $inserts->count(),
            'inserts' => InsertResource::collection($inserts)
        ]);
    }
} 