<?php

namespace App\Services;

use App\Models\LatestNotification;

class NotificationService
{
    public static function send(array $data)
    {
        return LatestNotification::create([
            'title'           => $data['title'],
            'message'         => $data['message'],
            'type'            => $data['type'] ?? 'system',
            'priority'        => $data['priority'] ?? 'normal',
            'sender_role'     => $data['sender_role'] ?? 'system',
            'sender_id'       => $data['sender_id'] ?? null,
            'company_id'      => $data['company_id'] ?? null,
            'target_type'     => $data['target_type'],
            'target_role'     => $data['target_role'] ?? null,
            'target_user_id'  => $data['target_user_id'] ?? null,
            'action_url'      => $data['action_url'] ?? null,
            'video_url'       => $data['video_url'] ?? null,
        ]);
    }
}
