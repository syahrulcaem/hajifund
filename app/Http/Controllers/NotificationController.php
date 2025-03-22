<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Ambil semua notifikasi (khusus admin)
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return response()->json($notifications, 200);
    }

    // Ambil notifikasi milik user yang login
    public function userNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->get();
        return response()->json($notifications, 200);
    }

    // Kirim notifikasi baru (broadcast atau ke user tertentu)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'nullable|in:UNREAD,READ',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id, // Null jika broadcast
            'title' => $request->title,
            'message' => $request->message,
            'status' => $request->status ?? 'UNREAD',
        ]);

        return response()->json([
            'message' => 'Notification sent successfully',
            'data' => $notification
        ], 201);
    }

    // Tandai notifikasi sebagai "READ"
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id()) // Pastikan user hanya bisa update notifikasi miliknya
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->update(['status' => 'READ']);

        return response()->json(['message' => 'Notification marked as read'], 200);
    }

    // Hapus notifikasi
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted'], 200);
    }
}
