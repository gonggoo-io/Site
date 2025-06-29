<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'data', 'read_at'];
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
} 