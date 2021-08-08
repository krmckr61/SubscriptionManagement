<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceApp extends Model
{

    protected $table = 'device_apps';

    protected $fillable = ['device_id', 'app_id', 'token'];

    public static function getFromToken(string $token)
    {
        return self::where('token', $token)->first();
    }

    public static function getDeviceFromToken(string $token)
    {
        return self::select('devices.*', 'device_apps.app_id')->join('devices', 'device_apps.device_id', '=', 'devices.id')->where('token', $token)->first();
    }

}
