<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\LatestNotification;

class AdminNotificationController extends Controller
{
     public function index()
    {
        $notifications = LatestNotification::latest()->paginate(20);

        return view('Admin.Notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('Admin.Notifications.create');
    }

    public function store(Request $request)
    {
        NotificationService::send([
            'title'       => $request->title,
            'message'     => $request->message,
            'type'        => $request->type, // system | update
            'priority'    => $request->priority,
            'sender_role' => 'super_admin',
            'sender_id'   => auth()->id(),
            'target_type' => $request->target_type, // all | company | role
            'company_id'  => $request->company_id ?? null,
        ]);

        return redirect()->back()->with('success', 'Notification sent successfully');
    }
}
