<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string',
            'link' => 'required|url',
            'participant_count' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
        ]);

        Post::create($request->all());

        return response()->json(['success' => true]);
    }
}
