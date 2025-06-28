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
        'user_id', 'title', 'description', 'image', 'link', 'price', 'people_count', 
        'per_person_count', 'address', 'bank', 'account_number', 'deadline', 'count'
    ];   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buys()
    {
        return $this->hasMany(Buy::class, 'insert_id');
    }

    public function activeBuys()
    {
        return $this->hasMany(Buy::class, 'insert_id')->whereNull('cancelled_at');
    }

    protected static function booted()
    {
        static::addGlobalScope('not_expired', function ($query) {
            $expiryDate = Carbon::now('Asia/Seoul')->subDays(7)->startOfSecond();
            $query->where('created_at', '>=', $expiryDate);
        });
    }
}
