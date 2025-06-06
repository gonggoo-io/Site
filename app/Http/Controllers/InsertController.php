<?php

namespace App\Http\Controllers;

use App\Models\Insert;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string',
            'link' => 'required|url',
            'participant_count' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['deadline'] = now()->setTimezone('Asia/Seoul')->addDays(7);

        Insert::create($data);

        return response()->json(['success' => true]);
    }
}
