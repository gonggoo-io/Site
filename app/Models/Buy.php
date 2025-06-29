<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    protected $table = 'buy';
    protected $fillable = ['user_id', 'insert_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function insert()
    {
        return $this->belongsTo(\App\Models\Insert::class, 'insert_id');
    }
}
