<?php

namespace App\Http\Controllers;

use App\Models\Insert;
use App\Models\Buy;
use App\Models\Notification;
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

            Buy::create([
                'user_id' => auth()->id(),
                'insert_id' => $insert->id,
                'created_at' => now()->setTimezone('Asia/Seoul'),
                'updated_at' => now()->setTimezone('Asia/Seoul')
            ]);

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

    public function destroy($id)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $insert = Insert::findOrFail($id);
            if ($insert->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own inserts'
                ], 403);
            }

            Buy::where('insert_id', $id)->delete();
            $insert->delete();

            Log::info('Insert deleted successfully:', ['insert_id' => $id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Insert deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting insert:', [
                'error' => $e->getMessage(),
                'insert_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete insert'
            ], 500);
        }
    }

    public function submitTrackingNumber(Request $request, $id)
    {
        try {
            Log::info('submitTrackingNumber called', [
                'insert_id' => $id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            if (!auth()->check()) {
                Log::error('User not authenticated in submitTrackingNumber');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'tracking_number' => 'required|string|max:255',
                'courier' => 'required|string|max:255'
            ]);

            $insert = Insert::with(['user', 'buys.user'])->findOrFail($id);
            Log::info('Insert found', ['insert' => $insert->toArray()]);
            
            if ($insert->user_id !== auth()->id()) {
                Log::error('User is not the owner', [
                    'insert_user_id' => $insert->user_id,
                    'current_user_id' => auth()->id()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Only the insert owner can submit tracking number'
                ], 403);
            }

            if ($insert->tracking_number && $insert->courier) {
                Log::warning('Tracking number already exists', [
                    'existing_tracking_number' => $insert->tracking_number,
                    'existing_courier' => $insert->courier
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking number has already been submitted for this insert'
                ], 400);
            }

            Log::info('Updating insert with tracking number', [
                'tracking_number' => $request->tracking_number,
                'courier' => $request->courier
            ]);

            $insert->update([
                'tracking_number' => $request->tracking_number,
                'courier' => $request->courier,
                'purchased_at' => now()->setTimezone('Asia/Seoul')
            ]);

            Log::info('Insert updated successfully', ['insert_id' => $id]);

            $activeBuys = $insert->buys()->whereNull('cancelled_at')->get();
            
            foreach ($activeBuys as $buy) {
                if ($buy->user_id === $insert->user_id) {
                    continue;
                }

                Notification::create([
                    'user_id' => $buy->user_id,
                    'data' => [
                        'user' => $insert->user->name,
                        'action' => '구매 완료',
                        'group' => $insert->title,
                        'date' => now()->setTimezone('Asia/Seoul')->format('Y-m-d H:i'),
                        'insert_id' => $insert->id,
                        'title' => '공구 상품 구매 완료',
                        'message' => "[{$insert->title}] 공구의 상품이 구매되었습니다.\n\n" .
                            "택배사: {$request->courier}\n" .
                            "운송장번호: {$request->tracking_number}\n\n" .
                            "입금 정보\n" .
                            "은행: {$insert->bank}\n" .
                            "계좌번호: {$insert->account_number}",
                        'type' => 'purchase_completed',
                        'courier' => $request->courier,
                        'tracking_number' => $request->tracking_number,
                        'bank' => $insert->bank,
                        'account_number' => $insert->account_number
                    ]
                ]);
            }

            Log::info('Tracking number submitted successfully:', [
                'insert_id' => $id,
                'tracking_number' => $request->tracking_number,
                'courier' => $request->courier,
                'notifications_sent' => $activeBuys->count() - 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking number submitted successfully',
                'notifications_sent' => $activeBuys->count() - 1
            ]);

        } catch (\Exception $e) {
            Log::error('Error submitting tracking number:', [
                'error' => $e->getMessage(),
                'insert_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit tracking number'
            ], 500);
        }
    }
}
