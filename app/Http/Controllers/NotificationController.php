<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LatestNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = LatestNotification::where(function ($query) use ($user) {

            // Global notifications (sent to all)
            $query->where('target_type', 'all');

            // User specific notifications
            $query->orWhere(function ($q) use ($user) {
                $q->where('target_type', 'user')
                  ->where('target_user_id', $user->id);
            });

            // Company notifications
            if (!empty($user->company_id)) {
                $query->orWhere(function ($q) use ($user) {
                    $q->where('target_type', 'company')
                      ->where('company_id', $user->company_id);
                });
            }

        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('Notification.index', compact('notifications'));
    }

     public function markAsRead($id)
    {
        LatestNotification::where('id', $id)
            ->where(function ($query) {
                $query->where('target_user_id', auth()->id())
                      ->orWhere('target_type', 'all');
            })
            ->update(['is_read' => 1]);

        return redirect()->back();
    }
}
