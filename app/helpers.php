<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

function getPurchaseDataFromMarket(string $receipt, string $os) {
//    $url = env($os . '_API_URL');
//    $headers = [
//        'username' => env($os . '_API_USERNAME'),
//        'password' => env($os . '_API_PASSWORD'),
//    ];
//    $body = json_encode(['receipt' => $receipt]);
//    $purchaseData = Http::withHeaders($headers)->withBody($body, 'application/json')->post($url)->json();

    $purchaseData = ['status' => true, 'expire_date' => '2022-01-01 00:00:00'];

    if(isset($purchaseData['status']) && isset($purchaseData['expire_date'])) {
        $purchaseData['expire_date'] = convertToTimezone($purchaseData['expire_date'], 'GMT-6');
        return $purchaseData;
    } else {
        return false;
    }
}

function convertToTimezone(string $date, string $currentTimezone) {
    return Carbon::createFromFormat('Y-m-d H:i:s', $date, $currentTimezone)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
}

function getCurrentTimestamp() {
    return date('Y-m-d H:i:s');
}
