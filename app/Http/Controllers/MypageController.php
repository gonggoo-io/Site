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
} 