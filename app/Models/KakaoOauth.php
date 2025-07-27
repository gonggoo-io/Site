<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KakaoOauth extends Model
{
    use HasFactory;

    protected $table = 'kakao_oauth';

    protected $fillable = [
        'user_id',
        'kakao_id',
        'nickname',
        'profile_image',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
