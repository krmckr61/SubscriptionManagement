<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    protected $table = 'purchases';

    protected $fillable = ['device_id', 'app_id', 'status', 'expire_date'];

    public static function getFromDeviceIdAppId(int $deviceId, int $appId)
    {
        return self::where([['device_id', $deviceId], ['app_id', $appId]])->first();
    }

}
