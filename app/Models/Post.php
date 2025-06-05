<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'item_name', 'link', 'participant_count', 'total_price'
    ];   
}
