<?php

namespace App\Http\Controllers;

use App\Models\Insert;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function show($id)
    {
        $insert = Insert::with(['user', 'buys', 'activeBuys'])->findOrFail($id);
        
        return Inertia::render('Content', [
            'insert' => $insert
        ]);
    }
} 