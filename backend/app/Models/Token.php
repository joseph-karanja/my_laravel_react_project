<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'token_management';
    protected $fillable = ['user_uuid', 'token', 'expires_at', 'created_at', 'updated_at'];
}
