<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    protected $table = 'devices';

    protected $primaryKey = 'id';

    protected $fillable = ['uid', 'language', 'os', 'token'];

    public static function getFromUid(string $uid)
    {
        return self::where('uid', $uid)->first();
    }

    public function apps()
    {
        return $this->hasMany(DeviceApp::class);
    }

    public function getTokenFromAppId(int $appId)
    {
        if($deviceApp = $this->apps()->select('token')->where('app_id', $appId)->first()) {
            return $deviceApp->token;
        } else {
            return false;
        }
    }

}
