<?php

namespace App\Http\Middleware;

use App\Models\DeviceApp;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('client-token');
        if ($token && ($deviceApp = DeviceApp::getDeviceFromToken($token))) {
            $request->merge(['deviceInfo' => $deviceApp]);
            return $next($request);
        } else {
            return new Response(['message' => 'Unauthorized request'], 401);
        }

    }
}
