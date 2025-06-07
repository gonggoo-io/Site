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
        'user_id', 'item', 'link', 'count', 'price', 'created', 'deadline'
    ];   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buys()
    {
        return $this->hasMany(Buy::class, 'insert_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('not_expired', function ($query) {
            $expiryDate = Carbon::now('Asia/Seoul')->subDays(7)->startOfSecond();
            $query->where('created_at', '>=', $expiryDate);
        });
    }
}
