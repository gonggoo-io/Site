<?php

namespace App\Http\Controllers;

use App\Models\Insert;
use App\Http\Resources\InsertResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsertController extends Controller
{
    public function index()
    {
        $inserts = Insert::with('user')->get();
        return InsertResource::collection($inserts);
    }

    public function show($id)
    {
        $insert = Insert::with(['user', 'buys'])->findOrFail($id);
        return new InsertResource($insert);
    }

    public function store(Request $request)
    {
        try {
            if (!auth()->check()) {
                Log::error('User not authenticated in InsertController::store');
                return response()->json([
                    'success' => false,
                    'error' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'link' => 'required|url',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'people_count' => 'required|integer|min:1',
                'per_person_count' => 'required|integer|min:1',
                'address' => 'required|string',
                'bank' => 'required|string',
                'account_number' => 'required|string',
            ]);

            $data = $request->all();
            $data['user_id'] = auth()->id();
            $data['deadline'] = now()->setTimezone('Asia/Seoul')->addDays(7)->toDateTimeString();
            $data['count'] = $data['people_count'] * $data['per_person_count'];

            Log::info('Creating insert with data:', $data);

            $insert = Insert::create($data);

            Log::info('Insert created successfully:', ['insert_id' => $insert->id]);

            return response()->json([
                'success' => true,
                'insert' => new InsertResource($insert)
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating insert:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
