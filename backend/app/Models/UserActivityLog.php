<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_activity_logs';
    protected $fillable = ['user_uuid', 'activity_type', 'ip_address', 'user_agent', 'created_at'];
}