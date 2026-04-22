<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAll(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return back();
    }

    public function readOne(Request $request, string $notificationId): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return back();
        }

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (! $notification) {
            return back()->with('error', 'Notifikasi tidak ditemukan.');
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $actionUrl = $notification->data['action_url'] ?? null;

        if (is_string($actionUrl) && $actionUrl !== '') {
            return redirect()->to($actionUrl);
        }

        return back();
    }
}
