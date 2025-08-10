<?php

namespace App\Http\Controllers;

use App\Models\Insert;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\UserResource;

class DashboardController extends Controller
{
    public function index()
    {
        $inserts = Insert::with(['user', 'buys'])->orderBy('created_at', 'desc')->get();
        
        return Inertia::render('Dashboard', [
            'inserts' => $inserts,
            'userId' => auth()->id(),
            'user' => auth()->check() ? new UserResource(auth()->user()) : null
        ]);
    }
} 