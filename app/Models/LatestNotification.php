<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestNotification extends Model
{

    protected $table = 'latest_notifications';
    protected $fillable = [
        'title',
        'message',
        'type',
        'priority',
        'sender_role',
        'sender_id',
        'company_id',
        'target_type',
        'target_role',
        'target_user_id',
        'action_url',
        'is_read'
    ];
}
