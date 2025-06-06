<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insert extends BaseModel
{
    protected $table = 'insert';
    protected $fillable = [
        'user_id', 'item_name', 'link', 'participant_count', 'total_price'
    ];   
}
