<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('portal.admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Đã đánh dấu tất cả là đã đọc.');
    }
}
