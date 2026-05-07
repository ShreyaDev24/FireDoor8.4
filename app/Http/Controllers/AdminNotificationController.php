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
        $request->validate([
            'title'        => 'required|string|max:255',
            'message'      => 'required|string',
            'type'         => 'required|string',
            'priority'     => 'required|string',
            'target_type'  => 'required|string',
            'update_video' => 'nullable', // 50MB
        ]);

        $videoPath = null;

        // ✅ Upload video to public/notification/video/
        if ($request->hasFile('update_video')) {

            $video      = $request->file('update_video');
            $fileName   = 'update_' . time() . '.' . $video->getClientOriginalExtension();
            $uploadPath = public_path('notification/video');

            // create directory if not exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $video->move($uploadPath, $fileName);

            // path to store in DB / notification payload
            $videoPath = 'notification/video/' . $fileName;
        }

        NotificationService::send([
            'title'        => $request->title,
            'message'      => $request->message,
            'type'         => $request->type,
            'priority'     => $request->priority,
            'sender_role'  => 'super_admin',
            'sender_id'    => auth()->id(),
            'target_type'  => $request->target_type,
            'company_id'   => $request->company_id ?? null,
            'video_url'    => $videoPath, // ✅ stored as public path
        ]);

        return redirect()->back()->with('success', 'Notification sent successfully');
    }
}
