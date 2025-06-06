<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Insert extends BaseModel
{
    use SoftDeletes;
    
    protected $table = 'insert';
    protected $fillable = [
        'user_id', 'item_name', 'link', 'participant_count', 'total_price', 'created_at'
    ];   

    protected static function booted()
    {
        static::addGlobalScope('not_expired', function ($query) {
            $expiryDate = Carbon::now('Asia/Seoul')->subDays(7)->startOfSecond();
            $query->where('created_at', '>=', $expiryDate);
        });
    }
}
