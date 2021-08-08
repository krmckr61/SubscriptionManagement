<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceApp;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    private function generateToken(string $uid, int $appId): string
    {
        //example
        return Hash::make($uid . $appId);
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->post(), [
            'uid' => 'required',
            'app_id' => 'required|integer',
            'language' => 'required|string',
            'os' => 'in:GOOGLE,IOS'
        ]);

        if (!$validation->fails()) {
            $uid = $request->input('uid');
            $language = $request->input('language');
            $os = $request->input('os');

            //find or insert devices table
            $device = Device::getFromUid($uid);
            if (!$device) {
                $device = new Device();
                $device->uid = $uid;
                $device->language = $language;
                $device->os = $os;
                $device->save();
            }

            $appId = $request->input('app_id');

            //find or insert device_apps table
            $token = $device->getTokenFromAppId($appId);
            if (!$token) {
                $token = $this->generateToken($device->id, $appId);
                $device->apps()->save(new DeviceApp(['app_id' => $appId, 'token' => $token]));
            }

            return new Response(['client-token' => $token], 201);
        } else {
            return new Response(['message' => $validation->errors()->first()], 400);
        }
    }

    public function purchase(Request $request)
    {
        $validation = Validator::make($request->post(), [
            'receipt' => 'required|string',
        ]);

        $deviceInfo = $request->input('deviceInfo');
        $receipt = $request->input('receipt');


        if (!$validation->fails()) {
            if($purchaseData = getPurchaseDataFromMarket($receipt, $deviceInfo->os)) {
                $purchase = Purchase::getFromDeviceIdAppId($deviceInfo->id, $deviceInfo->app_id);
                if (!$purchase) {
                    $purchase = new Purchase();
                    $purchase->device_id = $deviceInfo->id;
                    $purchase->app_id = $deviceInfo->app_id;
                    $purchase->receipt = $request->input('receipt');
                }
                $purchase->status = $purchaseData['status'];
                $purchase->expire_date = $purchaseData['expire_date'];

                try {
                    $purchase->save();
                    return new Response($purchase);
                } catch (\Exception $exception) {
                    return new Response(['message' => 'An error occurred.'], 500);
                }
            } else {
                return new Response(['message' => 'An error occurred while retrieving purchase info from market.'], 403);
            }
        } else {
            return new Response(['message' => $validation->errors()->first()], 400);
        }
    }

    public function checkSubscription(Request $request)
    {
        $deviceInfo = $request->input('deviceInfo');
        if($purchase = Purchase::getFromDeviceIdAppId($deviceInfo->id, $deviceInfo->app_id)) {
            return $purchase;
        } else {
            return new Response([], 204);
        }
    }

}
