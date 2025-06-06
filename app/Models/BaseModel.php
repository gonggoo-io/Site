<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Carbon\Carbon;

class BaseModel extends Model
{
    protected function asDateTime($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }

        $date = parent::asDateTime($value);
        return $date->setTimezone('Asia/Seoul');
    }

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    protected function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Seoul');
    }

    protected function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Seoul');
    }

    public function setCreatedAt($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->setTimezone('Asia/Seoul');
    }

    public function setUpdatedAt($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->setTimezone('Asia/Seoul');
    }
} 