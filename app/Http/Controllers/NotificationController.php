<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([], 401);
        }

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($n) {
                $data = $n->data;
                return [
                    'id' => $n->id,
                    'user' => $data['user'] ?? null,
                    'action' => $data['action'] ?? null,
                    'group' => $data['group'] ?? null,
                    'date' => $data['date'] ?? $n->created_at->format('Y-m-d H:i'),
                    'read_at' => $n->read_at,
                    'title' => $data['title'] ?? $data['group'] ?? null,
                    'insert_id' => $data['insert_id'] ?? null,
                    'type' => $data['type'] ?? null,
                    'courier' => $data['courier'] ?? null,
                    'tracking_number' => $data['tracking_number'] ?? null,
                    'bank' => $data['bank'] ?? null,
                    'account_number' => $data['account_number'] ?? null,
                    'message' => $data['message'] ?? null,
                ];
            });

        return response()->json($notifications);
    }

    public function stream(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            abort(401);
        }
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        while (true) {
            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($n) {
                    $data = $n->data;
                    return [
                        'id' => $n->id,
                        'user' => $data['user'] ?? null,
                        'action' => $data['action'] ?? null,
                        'group' => $data['group'] ?? null,
                        'date' => $data['date'] ?? $n->created_at->format('Y-m-d H:i'),
                        'read_at' => $n->read_at,
                        'title' => $data['title'] ?? $data['group'] ?? null,
                        'insert_id' => $data['insert_id'] ?? null,
                        'type' => $data['type'] ?? null,
                        'courier' => $data['courier'] ?? null,
                        'tracking_number' => $data['tracking_number'] ?? null,
                        'bank' => $data['bank'] ?? null,
                        'account_number' => $data['account_number'] ?? null,
                        'message' => $data['message'] ?? null,
                    ];
                });
            $unreadCount = Notification::where('user_id', $userId)->whereNull('read_at')->count();
            echo "data: " . json_encode(['notifications' => $notifications, 'unread_count' => $unreadCount]) . "\n\n";
            ob_flush();
            flush();
            sleep(5);
        }
    }

    public function markAsRead(Request $request)
    {
        $userId = Auth::id();
        $id = $request->input('id');
        if ($id) {
            $notification = Notification::where('user_id', $userId)->where('id', $id)->first();
            if ($notification) {
                $notification->read_at = now();
                $notification->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false], 404);
        } else {
            Notification::where('user_id', $userId)->whereNull('read_at')->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        }
    }
} 